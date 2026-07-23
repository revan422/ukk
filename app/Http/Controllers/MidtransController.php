<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Flight;
use App\Models\Seat;
use App\Models\Passenger;
use App\Services\MidtransService;

class MidtransController extends Controller
{
    protected $midtrans;

    public function __construct(MidtransService $midtrans)
    {
        $this->midtrans = $midtrans;
    }

    /**
     * Create a Snap transaction for the given booking.
     * Called via AJAX after booking is created.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createTransaction(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
        ]);

        $booking = Booking::with(['flight.airline', 'flight.departureAirport', 'flight.arrivalAirport', 'passenger', 'user', 'payment'])
            ->findOrFail($bookingId = $request->booking_id);

        // Ensure booking belongs to authenticated user
        if ($booking->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Check if booking already has a snap token that's still valid
        $payment = $booking->payment;
        if ($payment && $payment->snap_token && $payment->transaction_status === 'pending') {
            // Return existing token if payment is still pending
            return response()->json([
                'snap_token' => $payment->snap_token,
                'snap_url' => $this->midtrans->getSnapJsUrl(),
                'client_key' => $this->midtrans->getClientKey(),
            ]);
        }

        // Get seat by seat_number from booking
        $seat = Seat::where('flight_id', $booking->flight_id)
                    ->where('seat_number', $booking->seat_number)
                    ->first();

        $params = $this->midtrans->buildTransactionParams(
            $booking,
            $booking->flight,
            $booking->passenger,
            $seat ?? new Seat()
        );

        try {
            $snapToken = $this->midtrans->createSnapToken($params);

            // Update payment record with snap token and order_id
            if ($payment) {
                $payment->update([
                    'snap_token' => $snapToken,
                    'transaction_id' => $params['transaction_details']['order_id'],
                    'transaction_status' => 'pending',
                ]);
            } else {
                // Create payment if doesn't exist
                $payment = Payment::create([
                    'booking_id' => $booking->id,
                    'amount' => $booking->total_price,
                    'payment_method' => 'midtrans_snap',
                    'payment_status' => 'PENDING',
                    'transaction_id' => $params['transaction_details']['order_id'],
                    'snap_token' => $snapToken,
                    'transaction_status' => 'pending',
                ]);
            }

            return response()->json([
                'snap_token' => $snapToken,
                'snap_url' => $this->midtrans->getSnapJsUrl(),
                'client_key' => $this->midtrans->getClientKey(),
            ]);
        } catch (\Exception $e) {
            Log::error('Midtrans createTransaction error: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal membuat transaksi: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Handle Midtrans payment notification callback (webhook).
     * This endpoint receives HTTP POST from Midtrans after payment processing.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function callback(Request $request)
    {
        $payload = $request->all();

        Log::info('Midtrans callback received', $payload);

        // Verify signature key - NEVER trust unverified callbacks
        if (!$this->midtrans->verifySignature($payload)) {
            Log::warning('Midtrans callback invalid signature', $payload);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $orderId = $payload['order_id'] ?? '';
        $transactionStatus = $payload['transaction_status'] ?? '';
        $fraudStatus = $payload['fraud_status'] ?? '';
        $paymentType = $payload['payment_type'] ?? '';
        $transactionId = $payload['transaction_id'] ?? '';
        $grossAmount = $payload['gross_amount'] ?? 0;
        $statusCode = $payload['status_code'] ?? '';

        // Parse order_id: BOOKING-{booking_id}-{timestamp}
        if (!str_starts_with($orderId, 'BOOKING-')) {
            return response()->json(['message' => 'Invalid order ID format'], 400);
        }

        $parts = explode('-', $orderId);
        $bookingId = $parts[1] ?? null;

        if (!$bookingId) {
            return response()->json(['message' => 'Invalid order ID'], 400);
        }

        $booking = Booking::find($bookingId);
        if (!$booking) {
            Log::warning('Booking not found for callback', ['order_id' => $orderId, 'booking_id' => $bookingId]);
            return response()->json(['message' => 'Booking not found'], 404);
        }

        // Map transaction status to internal statuses
        $statusMap = $this->midtrans->mapTransactionStatus($transactionStatus, $fraudStatus);
        $bookingStatus = $statusMap['booking_status'];
        $paymentStatus = $statusMap['payment_status'];

        // Update booking status
        $updateData = ['status' => $bookingStatus];
        $booking->update($updateData);

        // Update or create payment record
        $payment = Payment::where('booking_id', $booking->id)->latest()->first();
        $paymentData = [
            'transaction_id' => $transactionId ?: $orderId,
            'transaction_status' => $transactionStatus,
            'fraud_status' => $fraudStatus,
            'payment_type' => $paymentType,
            'payment_status' => $paymentStatus,
            'paid_at' => in_array($paymentStatus, ['SUCCESS', 'success']) ? now() : null,
        ];

        if ($payment) {
            $payment->update($paymentData);
        } else {
            $paymentData['booking_id'] = $booking->id;
            $paymentData['amount'] = $grossAmount ?: $booking->total_price;
            $paymentData['payment_method'] = $paymentType ?: 'midtrans_snap';
            Payment::create($paymentData);
        }

        Log::info('Midtrans callback processed successfully', [
            'booking_id' => $booking->id,
            'order_id' => $orderId,
            'booking_status' => $bookingStatus,
            'payment_status' => $paymentStatus,
        ]);

        return response()->json(['message' => 'OK'], 200);
    }

    /**
     * Check transaction status from Midtrans API.
     *
     * @param string $bookingId
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkStatus($bookingId)
    {
        $booking = Booking::with('payment')->findOrFail($bookingId);

        // Ensure booking belongs to authenticated user or user is admin
        if ($booking->user_id !== Auth::id() && !in_array(Auth::user()->role ?? '', ['admin', 'manager', 'staff'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $payment = $booking->payment;
        if (!$payment || !$payment->transaction_id) {
            return response()->json(['error' => 'No transaction found'], 404);
        }

        // Try to get latest status from Midtrans API
        $status = $this->midtrans->getTransactionStatus($payment->transaction_id);

        if ($status) {
            // Update based on API response
            $transactionStatus = $status->transaction_status ?? '';
            $fraudStatus = $status->fraud_status ?? '';

            $statusMap = $this->midtrans->mapTransactionStatus($transactionStatus, $fraudStatus);
            $bookingStatus = $statusMap['booking_status'];
            $paymentStatus = $statusMap['payment_status'];

            return response()->json([
                'booking_status' => $booking->status,
                'payment_status' => $payment->payment_status,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
                'transaction_id' => $payment->transaction_id,
                'payment_type' => $payment->payment_type,
                'paid_at' => $payment->paid_at,
                'updated' => true,
            ]);
        }

        // Fallback to local data
        return response()->json([
            'booking_status' => $booking->status,
            'payment_status' => $payment->payment_status,
            'transaction_status' => $payment->transaction_status,
            'transaction_id' => $payment->transaction_id,
            'payment_type' => $payment->payment_type,
            'paid_at' => $payment->paid_at,
            'updated' => false,
        ]);
    }
}
