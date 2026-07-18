<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Flight;
use App\Models\Passenger;

class StaffController extends Controller
{
    public function index()
    {
        $pendingBookings = Booking::where('status', 'pending')->count();
        $confirmedBookings = Booking::where('status', 'confirmed')->count();
        $cancelledBookings = Booking::where('status', 'cancelled')->count();
        $todayBookings = Booking::whereDate('created_at', today())->count();
        $totalFlights = Flight::whereDate('departure_time', today())->count();
        $totalPassengers = Passenger::whereHas('bookings', function($q) {
            $q->where('status', 'confirmed');
        })->count();

        return view('staff.dashboard', compact(
            'pendingBookings', 'confirmedBookings', 'cancelledBookings',
            'todayBookings', 'totalFlights', 'totalPassengers'
        ));
    }

    // Kelola Booking (Reschedule/Cancel)
    public function bookings()
    {
        $bookings = Booking::with(['user', 'flight.airline', 'flight.departureAirport', 'flight.arrivalAirport', 'passenger'])
            ->orderBy('created_at', 'desc')->get();
        $flights = Flight::with(['airline', 'departureAirport', 'arrivalAirport'])
            ->where('departure_time', '>', now())
            ->orderBy('departure_time')->get();
        return view('staff.bookings', compact('bookings', 'flights'));
    }

    // Proses Reschedule
    public function reschedule(Request $request, $bookingId)
    {
        $request->validate([
            'new_flight_id' => 'required|exists:flights,id',
        ]);

        $booking = Booking::findOrFail($bookingId);
        $newFlight = Flight::findOrFail($request->new_flight_id);

        $booking->update([
            'flight_id' => $newFlight->id,
            'seat_number' => null,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Penerbangan berhasil di-reschedule. Customer harus memilih kursi ulang.');
    }

    // Proses Cancel/Refund
    public function cancel(Request $request, $bookingId)
    {
        $request->validate([
            'reason' => 'required|string',
        ]);

        $booking = Booking::findOrFail($bookingId);
        $booking->update(['status' => 'cancelled']);

        if ($booking->payment) {
            $booking->payment->update(['payment_status' => 'refunded']);
        }

        return back()->with('success', 'Booking berhasil dibatalkan. Refund akan diproses.');
    }

    // Handle Keluhan
    public function complaints()
    {
        $complaints = Booking::with(['user', 'flight.airline', 'passenger'])
            ->where('status', 'cancelled')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('staff.complaints', compact('complaints'));
    }

    // Passenger Manifest - Melihat penumpang per penerbangan
    public function manifest($flightId)
    {
        $flight = Flight::with(['airline', 'departureAirport', 'arrivalAirport', 'airplane'])->findOrFail($flightId);
        $passengers = Passenger::with(['bookings.user'])
            ->whereHas('bookings', function($q) use ($flightId) {
                $q->where('flight_id', $flightId)->where('status', 'confirmed');
            })
            ->get();

        return view('staff.manifest', compact('flight', 'passengers'));
    }

    // Flight Monitoring - Melihat jadwal penerbangan
    public function flightMonitoring()
    {
        $flights = Flight::with(['airline', 'departureAirport', 'arrivalAirport', 'airplane'])
            ->withCount(['bookings as confirmed_bookings' => function($q) {
                $q->where('status', 'confirmed');
            }])
            ->orderBy('departure_time')
            ->get();

        return view('staff.flights', compact('flights'));
    }
}
