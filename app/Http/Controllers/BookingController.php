<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Flight;
use App\Models\Passenger;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class BookingController extends Controller
{
    /**
     * 1. Membuat Data Booking & Passenger dari Session, lalu Redirect ke Halaman Detail
     */
    public function processBooking(Request $request)
    {
        $bookingData = session('booking_data');
        if (!$bookingData) {
            return redirect()->route('flights.index')
                ->with('error', 'Sesi pemesanan telah kadaluarsa. Silakan pilih penerbangan kembali.');
        }

        $flight = Flight::findOrFail($bookingData['flight_id']);
        
        // Simpan Booking
        $bookingCode = 'TRX-' . strtoupper(Str::random(8));
        $booking = Booking::create([
            'user_id'          => auth()->id(),
            'flight_id'        => $flight->id,
            'booking_code'     => $bookingCode,
            'total_passengers' => 1,
            'total_price'      => $bookingData['price'],
            'status'           => 'pending',
        ]);

        // Simpan Passenger
        Passenger::create([
            'booking_id'  => $booking->id,
            'full_name'   => $bookingData['passenger']['full_name'],
            'gender'      => $bookingData['passenger']['gender'] ?? 'male',
            'seat_number' => $bookingData['seat_number'] ?? $bookingData['seat_id'] ?? '1A',
        ]);

        // Hapus session booking sementara
        session()->forget('booking_data');

        // Redirect ke detail booking
        return redirect()->route('bookings.show', $booking->id);
    }

    /**
     * 2. Endpoint AJAX untuk route('payment.create')
     * Menggenerasi Snap Token Midtrans berdasarkan booking_id
     */
    public function createPaymentToken(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
        ]);

        $booking = Booking::with(['passenger', 'user'])->findOrFail($request->booking_id);

        // Konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id'     => $booking->booking_code,
                'gross_amount' => (int) $booking->total_price,
            ],
            'customer_details' => [
                'first_name' => $booking->passenger->full_name ?? auth()->user()->name,
                'email'      => auth()->user()->email,
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            // Buat / Perbarui Record Pembayaran
            Payment::updateOrCreate(
                ['booking_id' => $booking->id],
                [
                    'payment_method'   => 'Midtrans',
                    'amount'           => $booking->total_price,
                    'payment_status'   => 'pending',
                    'transaction_code' => $booking->booking_code,
                ]
            );

            // Kembalikan Response JSON untuk JavaScript di show.blade.php
            return response()->json(['snap_token' => $snapToken]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal terhubung ke Midtrans: ' . $e->getMessage()], 500);
        }
    }

    /**
     * 3. Menampilkan Halaman Detail Booking
     */
    public function show($id)
    {
        $booking = Booking::with([
            'flight.airline', 
            'flight.departureAirport', 
            'flight.arrivalAirport', 
            'passenger', 
            'payment'
        ])
        ->where('user_id', auth()->id())
        ->findOrFail($id);

        return view('bookings.show', compact('booking'));
    }
}
