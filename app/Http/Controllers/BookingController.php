<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
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
            'seat_id'   => 'required|exists:seats,id',
        ]);

        $seat = Seat::with('flight')->findOrFail($request->seat_id);

        $isOccupied = false;
        if (isset($seat->status) && $seat->status === 'booked') {
            $isOccupied = true;
        } elseif (isset($seat->is_available) && !$seat->is_available) {
            $isOccupied = true;
        }

        if ($isOccupied) {
            return back()->withErrors(['seat' => 'Kursi yang Anda pilih sudah dipesan. Silakan pilih kursi lain.']);
        }

        $seatPrice = $seat->price ?? $seat->flight->price ?? 0;

        session([
            'booking_data' => [
                'flight_id'   => $request->flight_id,
                'seat_id'     => $request->seat_id,
                'seat_number' => $seat->seat_number,
                'price'       => $seatPrice,
                'seat_class'  => $seat->class ?? $seat->seat_class ?? 'economy',
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
        $request->validate([
            'full_name'      => 'required|string|max:255',
            'date_of_birth'  => 'required|date|before:today',
            'id_card_number' => 'required|string|min:10|max:30',
            'gender'         => 'required|in:male,female',
        ]);

        $bookingData = session('booking_data');
        $bookingData['passenger'] = [
            'full_name'      => $request->full_name,
            'date_of_birth'  => $request->date_of_birth,
            'id_card_number' => $request->id_card_number,
            'gender'         => $request->gender,
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

        if (!isset($bookingData['flight_id']) || !isset($bookingData['seat_id'])) {
            return redirect()->route('flights.index')->withErrors(['error' => 'Data pemesanan tidak lengkap.']);
        }

        $flight = Flight::with(['airline', 'departureAirport', 'arrivalAirport'])->findOrFail($bookingData['flight_id']);
        $seat = Seat::findOrFail($bookingData['seat_id']);

        $clientKey = $this->midtrans->getClientKey();
        $snapUrl = $this->midtrans->getSnapJsUrl();

        return view('bookings.confirmation', compact('flight', 'seat', 'bookingData', 'clientKey', 'snapUrl'));
    }

    // Proses booking: simpan DB dalam transaksi aman & generate Snap Token
    public function processPayment(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('info', 'Silakan login terlebih dahulu untuk melanjutkan pembayaran.')
                ->with('redirect_after_login', route('bookings.confirmation'));
        }

        $bookingData = session('booking_data');

        if (!$bookingData || !isset($bookingData['passenger']) || !isset($bookingData['flight_id']) || !isset($bookingData['seat_id'])) {
            return redirect()->route('flights.index')->withErrors(['error' => 'Data booking tidak lengkap. Silakan ulangi pemesanan.']);
        }

        try {
            $bookingId = DB::transaction(function () use ($bookingData) {
                $passengerData = $bookingData['passenger'];

                // 1. Simpan Data Penumpang
                $passenger = Passenger::create([
                    'user_id'        => Auth::id(),
                    'full_name'      => $passengerData['full_name'],
                    'date_of_birth'  => $passengerData['date_of_birth'],
                    'id_card_number' => $passengerData['id_card_number'],
                    'gender'         => $passengerData['gender'],
                ]);

                $totalPrice = $bookingData['price'];

                // 2. Simpan Data Booking
                $booking = Booking::create([
                    'user_id'          => Auth::id(),
                    'flight_id'        => $bookingData['flight_id'],
                    'passenger_id'     => $passenger->id,
                    'booking_code'     => strtoupper(Str::random(8)),
                    'total_passengers' => 1,
                    'total_price'      => $totalPrice,
                    'seat_number'      => $bookingData['seat_number'],
                    'status'           => 'UNPAID',
                ]);

                // 3. Ambil relasi untuk payload Midtrans
                $flight = Flight::with(['airline', 'departureAirport', 'arrivalAirport'])->findOrFail($bookingData['flight_id']);
                $seat = Seat::findOrFail($bookingData['seat_id']);

                $snapToken = null;
                try {
                    $params = $this->midtrans->buildTransactionParams($booking, $flight, $passenger, $seat);
                    $snapToken = $this->midtrans->createSnapToken($params);
                } catch (\Exception $e) {
                    Log::error('Midtrans Snap Error: ' . $e->getMessage());
                }

                // 4. Simpan Payment Record
                Payment::create([
                    'booking_id'     => $booking->id,
                    'payment_method' => 'midtrans_snap',
                    'amount'         => $totalPrice,
                    'payment_status' => 'PENDING',
                    'transaction_id' => 'BOOKING-' . $booking->id . '-' . time(),
                    'snap_token'     => $snapToken,
                ]);

                // 5. Update Status Kursi & Kuota Penerbangan
                if (isset($seat->status)) {
                    $seat->update(['status' => 'booked']);
                }
                if (isset($seat->is_available)) {
                    $seat->update(['is_available' => false]);
                }

                $flight->update(['available_seats' => max(0, $flight->available_seats - 1)]);

                return $booking->id;
            });

            // Hapus session setelah berhasil disimpan
            session()->forget('booking_data');

            return redirect()->route('bookings.show', $bookingId);

        } catch (\Exception $e) {
            Log::error('Process Payment Error: ' . $e->getMessage());
            return redirect()->route('bookings.confirmation')->withErrors(['error' => 'Gagal memproses pemesanan. Silakan coba lagi.']);
        }
    }

    // Detail Booking & Pop-up Pembayaran Midtrans
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

        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        $clientKey = $this->midtrans->getClientKey();
        $snapUrl = $this->midtrans->getSnapJsUrl();
        $snapToken = optional($booking->payment)->snap_token;

        // Auto-generate Snap Token jika token belum ada/kosong dan status masih UNPAID
        if (!$snapToken && $booking->status === 'UNPAID') {
            try {
                $seat = Seat::where('flight_id', $booking->flight_id)
                    ->where('seat_number', $booking->seat_number)
                    ->first() ?? new Seat(['seat_class' => 'economy']);

                $params = $this->midtrans->buildTransactionParams($booking, $booking->flight, $booking->passenger, $seat);
                $snapToken = $this->midtrans->createSnapToken($params);

                if ($booking->payment) {
                    $booking->payment->update(['snap_token' => $snapToken]);
                }
            } catch (\Exception $e) {
                Log::error('Snap Regeneration Error: ' . $e->getMessage());
            }
        }

        return view('bookings.show', compact('booking', 'clientKey', 'snapUrl', 'snapToken'));
    }

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
