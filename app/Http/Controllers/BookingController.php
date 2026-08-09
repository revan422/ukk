<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Flight;
use App\Models\Passenger;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class BookingController extends Controller
{
    // 1. Pilih Kursi
    public function selectSeat($flightId)
    {
        $flight = Flight::findOrFail($flightId);
        return view('bookings.select_seat', compact('flight'));
    }

    public function processSeat(Request $request)
    {
        $request->validate([
            'flight_id'   => 'required|exists:flights,id',
            'seat_number' => 'required|string',
            'price'       => 'required|numeric',
        ]);

        session(['booking_data' => array_merge(session('booking_data', []), [
            'flight_id'   => $request->flight_id,
            'seat_number' => $request->seat_number,
            'price'       => $request->price,
        ])]);

        return redirect()->route('bookings.passenger');
    }

    // 2. Form Data Penumpang
    public function passengerForm()
    {
        if (!session('booking_data')) {
            return redirect()->route('flights.index')->with('error', 'Sesi telah kadaluarsa.');
        }

        return view('bookings.passenger');
    }

    public function processPassenger(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'gender'    => 'required|in:male,female',
        ]);

        $bookingData = session('booking_data', []);
        $bookingData['passenger'] = [
            'full_name' => $request->full_name,
            'gender'    => $request->gender,
        ];

        session(['booking_data' => $bookingData]);

        return redirect()->route('bookings.confirmation');
    }

    // 3. Konfirmasi Booking (Mengirimkan $seat agar tidak Undefined)
    public function confirmation()
    {
        $bookingData = session('booking_data');

        if (!$bookingData) {
            return redirect()->route('flights.index')->with('error', 'Sesi pemesanan telah kadaluarsa.');
        }

        $flight = Flight::with(['airline', 'departureAirport', 'arrivalAirport'])
            ->findOrFail($bookingData['flight_id']);

        // Mengirim $seat untuk dikonsumsi halaman Blade
        $seat = $bookingData['seat_number'] ?? '-';

        return view('bookings.confirmation', compact('bookingData', 'flight', 'seat'));
    }

    // 4. Simpan Booking ke DB & Redirect ke Halaman Detail
    public function processBooking(Request $request)
    {
        $bookingData = session('booking_data');
        if (!$bookingData) {
            return redirect()->route('flights.index')
                ->with('error', 'Sesi pemesanan telah kadaluarsa. Silakan pilih penerbangan kembali.');
        }

        $flight = Flight::findOrFail($bookingData['flight_id']);
        
        $bookingCode = 'TRX-' . strtoupper(Str::random(8));
        $booking = Booking::create([
            'user_id'          => auth()->id(),
            'flight_id'        => $flight->id,
            'booking_code'     => $bookingCode,
            'total_passengers' => 1,
            'total_price'      => $bookingData['price'],
            'status'           => 'pending',
        ]);

        Passenger::create([
            'booking_id'  => $booking->id,
            'full_name'   => $bookingData['passenger']['full_name'],
            'gender'      => $bookingData['passenger']['gender'] ?? 'male',
            'seat_number' => $bookingData['seat_number'] ?? '1A',
        ]);

        session()->forget('booking_data');

        return redirect()->route('bookings.show', $booking->id);
    }

    // 5. AJAX Request Midtrans Token
    public function createPaymentToken(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
        ]);

        $booking = Booking::with(['passenger', 'user'])->findOrFail($request->booking_id);

        $serverKey = config('services.midtrans.server_key', env('MIDTRANS_SERVER_KEY'));
        $isProduction = config('services.midtrans.is_production', env('MIDTRANS_IS_PRODUCTION', false));

        $midtransUrl = $isProduction 
            ? 'https://app.midtrans.com/snap/v1/transactions' 
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

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
            $response = Http::withBasicAuth($serverKey, '')
                ->withHeaders([
                    'Accept'       => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->post($midtransUrl, $params);

            if ($response->failed()) {
                return response()->json([
                    'error' => 'Gagal dari Midtrans: ' . ($response->json('error_messages.0') ?? $response->body())
                ], 500);
            }

            $snapToken = $response->json('token');

            Payment::updateOrCreate(
                ['booking_id' => $booking->id],
                [
                    'payment_method'   => 'Midtrans',
                    'amount'           => $booking->total_price,
                    'payment_status'   => 'pending',
                    'transaction_code' => $booking->booking_code,
                ]
            );

            return response()->json(['snap_token' => $snapToken]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal terhubung ke Midtrans: ' . $e->getMessage()], 500);
        }
    }

    // 6. Detail Booking
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

    // 7. Riwayat Booking User
    public function history()
    {
        $bookings = Booking::with(['flight.airline', 'payment'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('bookings.history', compact('bookings'));
    }

    // 8. Halaman Sukses
    public function success($id)
    {
        $booking = Booking::with(['flight.airline', 'passenger', 'payment'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        return view('bookings.success', compact('booking'));
    }
}
