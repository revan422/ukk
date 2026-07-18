<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Flight;
use App\Models\Seat;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Passenger;

class BookingController extends Controller
{
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

    // Proses form data penumpang - UPDATED dengan RajaOngkir
    public function processPassenger(Request $request)
    {
        $rules = [
            'full_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'id_card_number' => 'required|string|min:10|max:30',
            'gender' => 'required|in:male,female',
        ];

        if ($request->has('shipping_required')) {
            $rules['shipping_province_id'] = 'required';
            $rules['shipping_province_name'] = 'required';
            $rules['shipping_city_name'] = 'required';
            $rules['shipping_address'] = 'required|string';
            $rules['shipping_cost'] = 'required|numeric|min:0';
        }

        $request->validate($rules);

        $bookingData = session('booking_data');
        $bookingData['passenger'] = [
            'full_name' => $request->full_name,
            'date_of_birth' => $request->date_of_birth,
            'id_card_number' => $request->id_card_number,
            'gender' => $request->gender,
        ];

        if ($request->has('shipping_required')) {
            // Shipping cost sudah dihitung oleh JavaScript dari RajaOngkir Komerce API
            // dan dikirim via hidden field shipping_cost
            $bookingData['shipping'] = [
                'required' => true,
                'province_id' => $request->shipping_province_id,
                'province_name' => $request->shipping_province_name,
                'city_name' => $request->shipping_city_name,
                'address' => $request->shipping_address,
                'cost' => $request->shipping_cost,
            ];
        } else {
            $bookingData['shipping'] = [
                'required' => false,
                'cost' => 0,
            ];
        }

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

        return view('bookings.confirmation', compact('flight', 'seat', 'bookingData'));
    }

    // Proses pembayaran - LANGSUNG SUKSES (tanpa Midtrans) - UPDATED
    public function processPayment(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:bank_transfer,credit_card,debit_card,e_wallet',
        ]);

        $bookingData = session('booking_data');

        // Validasi: Pastikan booking_data ada
        if (!$bookingData) {
            return redirect()->route('flights.index')->withErrors(['error' => 'Data booking tidak ditemukan. Silakan mulai dari awal.']);
        }

        // Validasi: Pastikan semua data yang diperlukan ada
        $requiredKeys = ['flight_id', 'seat_id', 'seat_number', 'price', 'passenger'];
        foreach ($requiredKeys as $key) {
            if (!isset($bookingData[$key])) {
                return redirect()->route('bookings.confirmation')->withErrors(['error' => "Data {$key} tidak ditemukan. Silakan ulangi proses pemesanan."]);
            }
        }

        // Validasi: Pastikan data passenger ada dan lengkap
        if (!isset($bookingData['passenger']) || empty($bookingData['passenger'])) {
            return redirect()->route('bookings.passenger')->withErrors(['error' => 'Data penumpang tidak lengkap. Silakan isi data penumpang terlebih dahulu.']);
        }

        // Validasi passenger fields - UPDATED
        $passengerData = $bookingData['passenger'];
        if (!isset($passengerData['full_name']) || !isset($passengerData['date_of_birth']) || !isset($passengerData['id_card_number']) || !isset($passengerData['gender'])) {
            return redirect()->route('bookings.passenger')->withErrors(['error' => 'Data penumpang tidak lengkap. Pastikan semua field terisi.']);
        }

        // Buat Passenger - UPDATED
        $passenger = Passenger::create([
            'user_id' => Auth::id(),
            'full_name' => $passengerData['full_name'],
            'date_of_birth' => $passengerData['date_of_birth'],
            'id_card_number' => $passengerData['id_card_number'],
            'gender' => $passengerData['gender'],
        ]);

        // Buat Booking dengan status CONFIRMED (langsung sukses) - UPDATE dengan RajaOngkir
        $shippingCost = isset($bookingData['shipping']['required']) && $bookingData['shipping']['required'] ? $bookingData['shipping']['cost'] : 0.00;
        $totalPrice = $bookingData['price'] + $shippingCost;

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'flight_id' => $bookingData['flight_id'],
            'passenger_id' => $passenger->id,
            'booking_code' => strtoupper(Str::random(8)),
            'total_passengers' => 1,
            'total_price' => $totalPrice,
            'shipping_cost' => $shippingCost,
            'shipping_province' => $bookingData['shipping']['province_name'] ?? null,
            'shipping_city' => $bookingData['shipping']['city_name'] ?? null,
            'shipping_address' => $bookingData['shipping']['address'] ?? null,
            'seat_number' => $bookingData['seat_number'],
            'status' => 'confirmed',
        ]);

        // Buat Payment dengan status SUCCESS - UPDATE dengan RajaOngkir
        Payment::create([
            'booking_id' => $booking->id,
            'payment_method' => $request->payment_method,
            'amount' => $totalPrice,
            'payment_status' => 'success',
            'transaction_id' => 'TRX-' . strtoupper(Str::random(10)),
            'paid_at' => now(),
        ]);

        // Update status kursi
        $seat = Seat::findOrFail($bookingData['seat_id']);
        $seat->update(['status' => 'booked']);

        // Update available_seats di flight
        $flight = Flight::findOrFail($bookingData['flight_id']);
        $flight->update(['available_seats' => $flight->available_seats - 1]);

        // Hapus session
        session()->forget('booking_data');

        // Langsung redirect ke halaman sukses
        return redirect()->route('bookings.success', $booking->id);
    }

    // Tampilkan halaman sukses
    public function success($bookingId)
    {
        $booking = Booking::with(['flight.airline', 'flight.departureAirport', 'flight.arrivalAirport', 'passenger', 'payment'])->findOrFail($bookingId);

        return view('bookings.success', compact('booking'));
    }

    // Tampilkan halaman riwayat booking
    public function history()
    {
        $bookings = Booking::where('user_id', Auth::id())
            ->with(['flight.airline', 'flight.departureAirport', 'flight.arrivalAirport', 'passenger'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('bookings.history', compact('bookings'));
    }
}
