<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use Midtrans\Notification;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$merchantId = config('midtrans.merchant_id');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Get Midtrans Client Key for frontend Snap.js
     */
    public function getClientKey(): string
    {
        return config('midtrans.client_key');
    }

    /**
     * Get Snap JS URL based on environment
     */
    public function getSnapJsUrl(): string
    {
        return config('midtrans.is_production')
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
    }

    /**
     * Create Snap transaction and return the Snap token.
     *
     * @param array $params Transaction details, customer details, item details
     * @return string Snap Token
     * @throws \Exception
     */
    public function createSnapToken(array $params): string
    {
        $snapResponse = Snap::createTransaction($params);
        return $snapResponse->token;
    }

    /**
     * Build standard transaction payload for booking.
     *
     * @param \App\Models\Booking $booking
     * @param \App\Models\Flight $flight
     * @param \App\Models\Passenger $passenger
     * @param \App\Models\Seat $seat
     * @return array
     */
    public function buildTransactionParams($booking, $flight, $passenger, $seat): array
    {
        $orderId = 'BOOKING-' . $booking->id . '-' . now()->timestamp;

        $customerName = $passenger->full_name ?? $passenger->name ?? 'Customer';
        $customerEmail = optional($booking->user)->email ?? 'customer@example.com';
        $customerPhone = optional($booking->user)->phone ?? '08123456789';

        $itemDetails = [
            [
                'id' => 'TICKET-' . $flight->flight_number,
                'price' => (int) $booking->total_price,
                'quantity' => 1,
                'name' => 'Tiket ' . ($flight->airline->name ?? 'Maskapai') . ' - ' .
                          ($flight->flight_number ?? '') . ' (' .
                          ucfirst($seat->seat_class ?? 'Economy') . ') - ' .
                          ($flight->departureAirport->code ?? '') . ' → ' .
                          ($flight->arrivalAirport->code ?? ''),
            ],
        ];

        return [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $booking->total_price,
            ],
            'customer_details' => [
                'first_name' => $customerName,
                'email' => $customerEmail,
                'phone' => $customerPhone,
            ],
            'item_details' => $itemDetails,
        ];
    }

    /**
     * Verify Midtrans notification signature.
     *
     * @param array $notification
     * @return bool
     */
    public function verifySignature(array $notification): bool
    {
        $orderId = $notification['order_id'] ?? '';
        $statusCode = $notification['status_code'] ?? '';
        $grossAmount = $notification['gross_amount'] ?? '';
        $serverKey = config('midtrans.server_key');
        $signatureKey = $notification['signature_key'] ?? '';

        $computed = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        return hash_equals($computed, $signatureKey);
    }

    /**
     * Map Midtrans transaction status to internal booking status.
     *
     * @param string $transactionStatus
     * @param string|null $fraudStatus
     * @return array ['booking_status', 'payment_status']
     */
    public function mapTransactionStatus(string $transactionStatus, ?string $fraudStatus = null): array
    {
        $bookingStatus = 'UNPAID';
        $paymentStatus = 'PENDING';

        switch ($transactionStatus) {
            case 'capture':
                // For credit card, check fraud status
                if ($fraudStatus === 'accept') {
                    $bookingStatus = 'PAID';
                    $paymentStatus = 'SUCCESS';
                } elseif ($fraudStatus === 'challenge') {
                    $bookingStatus = 'PENDING';
                    $paymentStatus = 'PENDING';
                } else {
                    $bookingStatus = 'FAILED';
                    $paymentStatus = 'FAILED';
                }
                break;

            case 'settlement':
                $bookingStatus = 'PAID';
                $paymentStatus = 'SUCCESS';
                break;

            case 'pending':
                $bookingStatus = 'PENDING';
                $paymentStatus = 'PENDING';
                break;

            case 'deny':
                $bookingStatus = 'FAILED';
                $paymentStatus = 'FAILED';
                break;

            case 'expire':
                $bookingStatus = 'FAILED';
                $paymentStatus = 'EXPIRED';
                break;

            case 'cancel':
                $bookingStatus = 'CANCELLED';
                $paymentStatus = 'CANCELLED';
                break;

            case 'refund':
            case 'partial_refund':
                $bookingStatus = 'REFUNDED';
                $paymentStatus = 'REFUNDED';
                break;

            default:
                $bookingStatus = 'PENDING';
                $paymentStatus = 'PENDING';
                break;
        }

        return [
            'booking_status' => $bookingStatus,
            'payment_status' => $paymentStatus,
        ];
    }

    /**
     * Get transaction status from Midtrans API by Order ID.
     *
     * @param string $orderId
     * @return object|null
     */
    public function getTransactionStatus(string $orderId)
    {
        try {
            return Transaction::status($orderId);
        } catch (\Exception $e) {
            return null;
        }
    }
}
