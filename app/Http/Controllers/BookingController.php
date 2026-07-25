<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Models\Flight;
use App\Models\Seat;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Passenger;
use App\Services\MidtransService;

class BookingController extends Controller
{
    protected $midtrans;

    public function __construct(MidtransService $midtrans)
    {
        $this->midtrans = $midtrans;
    }

    // Tampilkan halaman pemilihan kursi
    public function selectSeat($flightId)
    {
        $flight = Flight::with(['airline', 'departureAirport', 'arrivalAirport', 'seats'])->findOrFail($flightId);
        $seats = $flight->seats;

        // Get selected seat from session if exists
        $selectedSeat = session('booking_data.seat_number');
        $selectedSeatId = session('booking_data.seat_id');
        $selectedSeatPrice = session('booking_data.price');

        return view('bookings.select-seat', compact('flight', 'seats', 'selectedSeat', 'selectedSeatId', 'selectedSeatPrice'));
    }

    // Proses pemilihan kursi
    public function processSeat(Request $request)
    {
        $request->validate([
            'flight_id' => 'required|exists:flights,id',
            'seat_id' => 'required|exists:seats,id',
        ]);

        $seat = Seat::findOrFail($request->seat_id);

        // Cek apakah kursi masih tersedia
        if ($seat->status !== 'available') {
            return back()->withErrors(['seat' => 'Kursi yang Anda pilih sudah dipesan. Silakan pilih kursi lain.']);
        }

        // Simpan di session untuk langkah berikutnya
        session([
            'booking_data' => [
                'flight_id' => $request->flight_id,
                'seat_id' => $request->seat_id,
                'seat_number' => $seat->seat_number,
                'price' => $seat->price ?? $seat->flight->price,
                'seat_class' => $seat->seat_class,
            ]
        ]);

        return redirect()->route('bookings.passenger');
    }

    // Tampilkan halaman form data penumpang
    public function passengerForm()
    {
        if (!session('booking_data')) {
            return redirect()->route('flights.index')->withErrors(['error' => 'Silakan pilih penerbangan terlebih dahulu.']);
        }

        $bookingData = session('booking_data');
        $flight = Flight::with(['airline', 'departureAirport', 'arrivalAirport'])->findOrFail($bookingData['flight_id']);

        return view('bookings.passenger', compact('flight', 'bookingData'));
    }

    // Proses form data penumpang
    public function processPassenger(Request $request)
    {
        $rules = [
            'full_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'id_card_number' => 'required|string|min:10|max:30',
            'gender' => 'required|in:male,female',
        ];

        $request->validate($rules);

        $bookingData = session('booking_data');
        $bookingData['passenger'] = [
            'full_name' => $request->full_name,
            'date_of_birth' => $request->date_of_birth,
            'id_card_number' => $request->id_card_number,
            'gender' => $request->gender,
        ];

        $bookingData['shipping'] = [
            'required' => false,
            'cost' => 0,
        ];

        session(['booking_data' => $bookingData]);

        return redirect()->route('bookings.confirmation');
    }

    // Tampilkan halaman konfirmasi
    public function confirmation()
    {
        if (!session('booking_data')) {
            return redirect()->route('flights.index')->withErrors(['error' => 'Silakan pilih penerbangan terlebih dahulu.']);
        }

        $bookingData = session('booking_data');

        // Validasi data yang diperlukan
        if (!isset($bookingData['flight_id']) || !isset($bookingData['seat_id'])) {
            return redirect()->route('flights.index')->withErrors(['error' => 'Data pemesanan tidak lengkap.']);
        }

        $flight = Flight::with(['airline', 'departureAirport', 'arrivalAirport'])->findOrFail($bookingData['flight_id']);
        $seat = Seat::findOrFail($bookingData['seat_id']);

        $clientKey = $this->midtrans->getClientKey();
        $snapUrl = $this->midtrans->getSnapJsUrl();

        return view('bookings.confirmation', compact('flight', 'seat', 'bookingData', 'clientKey', 'snapUrl'));
    }

    // Proses booking: simpan ke database, generate Snap token
    public function processPayment(Request $request)
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            // Simpan booking data di session dan redirect ke login
            return redirect()->route('login')
                ->with('info', 'Silakan login terlebih dahulu untuk melanjutkan pembayaran.')
                ->with('redirect_after_login', route('bookings.confirmation'));
        }

        $bookingData = session('booking_data');

        if (!$bookingData) {
            return redirect()->route('flights.index')->withErrors(['error' => 'Data booking tidak ditemukan. Silakan mulai dari awal.']);
        }

        $requiredKeys = ['flight_id', 'seat_id', 'seat_number', 'price', 'passenger'];
        foreach ($requiredKeys as $key) {
            if (!isset($bookingData[$key])) {
                return redirect()->route('bookings.confirmation')->withErrors(['error' => "Data {$key} tidak ditemukan. Silakan ulangi proses pemesanan."]);
            }
        }

        $passengerData = $bookingData['passenger'] ?? null;
        if (!$passengerData || !isset($passengerData['full_name']) || !isset($passengerData['date_of_birth']) || !isset($passengerData['id_card_number']) || !isset($passengerData['gender'])) {
            return redirect()->route('bookings.passenger')->withErrors(['error' => 'Data penumpang tidak lengkap. Pastikan semua field terisi.']);
        }

        // Create passenger
        $passenger = Passenger::create([
            'user_id' => Auth::id(),
            'full_name' => $passengerData['full_name'],
            'date_of_birth' => $passengerData['date_of_birth'],
            'id_card_number' => $passengerData['id_card_number'],
            'gender' => $passengerData['gender'],
        ]);

        $totalPrice = $bookingData['price'];

        // Create booking with UNPAID status
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'flight_id' => $bookingData['flight_id'],
            'passenger_id' => $passenger->id,
            'booking_code' => strtoupper(Str::random(8)),
            'total_passengers' => 1,
            'total_price' => $totalPrice,
            'seat_number' => $bookingData['seat_number'],
            'status' => 'UNPAID',
        ]);

        // Create payment record
        Payment::create([
            'booking_id' => $booking->id,
            'payment_method' => 'midtrans_snap',
            'amount' => $totalPrice,
            'payment_status' => 'PENDING',
            'transaction_id' => 'BOOKING-' . $booking->id . '-' . now()->timestamp,
        ]);

        // Lock seat immediately to avoid double booking
        $seat = Seat::findOrFail($bookingData['seat_id']);
        $seat->update(['status' => 'booked']);

        $flight = Flight::findOrFail($bookingData['flight_id']);
        $flight->update(['available_seats' => max(0, $flight->available_seats - 1)]);

        // Clear session booking data
        session()->forget('booking_data');

        // Redirect to booking detail page where user can pay with Snap popup
        return redirect()->route('bookings.show', $booking->id);
    }

    // Tampilkan halaman detail booking dengan Snap payment
    public function show($bookingId)
    {
        $booking = Booking::with([
            'flight.airline',
            'flight.departureAirport',
            'flight.arrivalAirport',
            'passenger',
            'payment',
            'user'
        ])->findOrFail($bookingId);

        // Ensure booking belongs to authenticated user
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $clientKey = $this->midtrans->getClientKey();
        $snapUrl = $this->midtrans->getSnapJsUrl();

        return view('bookings.show', compact('booking', 'clientKey', 'snapUrl'));
    }

    // Tampilkan halaman sukses
    public function success($bookingId)
    {
        $booking = Booking::with([
            'flight.airline',
            'flight.departureAirport',
            'flight.arrivalAirport',
            'passenger',
            'payment'
        ])->findOrFail($bookingId);

        return view('bookings.success', compact('booking'));
    }

    // Tampilkan halaman riwayat booking
    public function history()
    {
        $bookings = Booking::where('user_id', Auth::id())
            ->with([
                'flight.airline',
                'flight.departureAirport',
                'flight.arrivalAirport',
                'passenger',
                'payment'
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('bookings.history', compact('bookings'));
    }
}
