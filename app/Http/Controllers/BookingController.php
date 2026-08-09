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
    public function processPayment(Request $request)
    {
        // 1. Cek apakah session booking_data tersedia
        $bookingData = session('booking_data');
        if (!$bookingData) {
            return redirect()->route('flights.index')->with('error', 'Sesi pemesanan telah kadaluarsa. Silakan pilih penerbangan kembali.');
        }

        $flight = Flight::findOrFail($bookingData['flight_id']);
        
        // 2. Buat Data Booking
        $bookingCode = 'TRX-' . strtoupper(Str::random(8));
        $booking = Booking::create([
            'user_id'          => auth()->id(),
            'flight_id'        => $flight->id,
            'booking_code'     => $bookingCode,
            'total_passengers' => 1,
            'total_price'      => $bookingData['price'],
            'status'           => 'pending',
        ]);

        // 3. Buat Data Passenger (Sesuai PDM)
        Passenger::create([
            'booking_id'  => $booking->id,
            'full_name'   => $bookingData['passenger']['full_name'],
            'gender'      => $bookingData['passenger']['gender'] ?? 'male',
            'seat_number' => $bookingData['seat_number'] ?? $bookingData['seat_id'] ?? '1A',
        ]);

        // 4. Konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id'     => $bookingCode,
                'gross_amount' => (int) $bookingData['price'],
            ],
            'customer_details' => [
                'first_name' => $bookingData['passenger']['full_name'],
                'email'      => auth()->user()->email,
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal terhubung ke Midtrans: ' . $e->getMessage());
        }

        // 5. Buat Record Pembayaran Awal (Sesuai PDM)
        Payment::create([
            'booking_id'       => $booking->id,
            'payment_method'   => 'Midtrans',
            'amount'           => $bookingData['price'],
            'payment_status'   => 'pending',
            'transaction_code' => $bookingCode,
        ]);

        // Hapus session booking sementara
        session()->forget('booking_data');

        // Redirect ke detail booking membawa snapToken
        return redirect()->route('bookings.show', $booking->id)->with('snapToken', $snapToken);
    }
}
