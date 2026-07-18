<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Flight;
use App\Models\User;
use App\Models\Airline;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ManagerController extends Controller
{
    public function index()
    {
        $totalRevenue = Booking::where('status', 'confirmed')->sum('total_price');
        $totalBookings = Booking::where('status', 'confirmed')->count();
        $avgTicketPrice = $totalBookings > 0 ? $totalRevenue / $totalBookings : 0;
        $totalCustomers = User::where('role', 'customer')->count();
        $totalFlights = Flight::count();
        $cancellationRate = Booking::count() > 0 ? (Booking::where('status', 'cancelled')->count() / Booking::count()) * 100 : 0;

        $revenueByAirline = DB::table('bookings')
            ->join('flights', 'bookings.flight_id', '=', 'flights.id')
            ->join('airlines', 'flights.airline_id', '=', 'airlines.id')
            ->where('bookings.status', 'confirmed')
            ->select('airlines.name as airline_name', DB::raw('SUM(bookings.total_price) as total_revenue'), DB::raw('COUNT(*) as total_bookings'))
            ->groupBy('airlines.name')
            ->orderByDesc('total_revenue')
            ->get();

        $monthlyBookings = DB::table('bookings')
            ->where('status', 'confirmed')
            ->where('created_at', '>=', now()->subMonths(6))
            ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"), DB::raw('COUNT(*) as total'), DB::raw('SUM(total_price) as revenue'))
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
            ->orderBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
            ->get();

        $topRoutes = DB::table('bookings')
            ->join('flights', 'bookings.flight_id', '=', 'flights.id')
            ->join('airports as dep', 'flights.departure_airport_id', '=', 'dep.id')
            ->join('airports as arr', 'flights.arrival_airport_id', '=', 'arr.id')
            ->where('bookings.status', 'confirmed')
            ->select('dep.code as from_code', 'arr.code as to_code', DB::raw('COUNT(*) as total_bookings'))
            ->groupBy('dep.code', 'arr.code')
            ->orderByDesc('total_bookings')
            ->limit(5)
            ->get();

        $topAirlines = Airline::withCount(['flights', 'flights as total_bookings' => function($q) {
                $q->join('bookings', 'bookings.flight_id', '=', 'flights.id')
                  ->where('bookings.status', 'confirmed');
            }])
            ->orderByDesc('total_bookings')
            ->limit(5)
            ->get();

        return view('manager.dashboard', compact(
            'totalRevenue', 'totalBookings', 'avgTicketPrice', 'totalCustomers',
            'totalFlights', 'cancellationRate', 'revenueByAirline', 'monthlyBookings',
            'topRoutes', 'topAirlines'
        ));
    }

    public function reports()
    {
        $bookings = Booking::with(['flight.airline', 'user'])
            ->where('status', 'confirmed')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('manager.reports', compact('bookings'));
    }

    public function performance()
    {
        $flights = Flight::with(['airline', 'departureAirport', 'arrivalAirport', 'bookings'])
            ->withCount('bookings')
            ->orderBy('bookings_count', 'desc')
            ->get();

        return view('manager.performance', compact('flights'));
    }

    public function revenueAnalytics()
    {
        $dailyRevenue = DB::table('bookings')
            ->where('status', 'confirmed')
            ->where('created_at', '>=', now()->subDays(30))
            ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d') as date"), DB::raw('SUM(total_price) as revenue'), DB::raw('COUNT(*) as bookings'))
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))
            ->orderBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))
            ->get();

        $weeklyRevenue = DB::table('bookings')
            ->where('status', 'confirmed')
            ->where('created_at', '>=', now()->subMonths(3))
            ->select(DB::raw("DATE_FORMAT(created_at, '%x-W%v') as week"), DB::raw('SUM(total_price) as revenue'), DB::raw('COUNT(*) as bookings'))
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%x-W%v')"))
            ->orderBy(DB::raw("DATE_FORMAT(created_at, '%x-W%v')"))
            ->get();

        $monthlyRevenue = DB::table('bookings')
            ->where('status', 'confirmed')
            ->where('created_at', '>=', now()->subYear())
            ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"), DB::raw('SUM(total_price) as revenue'), DB::raw('COUNT(*) as bookings'))
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
            ->orderBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
            ->get();

        return view('manager.revenue', compact('dailyRevenue', 'weeklyRevenue', 'monthlyRevenue'));
    }

    public function occupancyRate()
    {
        $flights = Flight::with(['airline', 'departureAirport', 'arrivalAirport'])
            ->withCount(['bookings as confirmed_bookings' => function($q) {
                $q->where('status', 'confirmed');
            }])
            ->orderBy('departure_time', 'desc')
            ->get()
            ->map(function($flight) {
                $flight->occupancy_percentage = $flight->available_seats > 0
                    ? round(($flight->confirmed_bookings / $flight->available_seats) * 100, 1)
                    : 0;
                return $flight;
            });

        $airlineOccupancy = DB::table('bookings')
            ->join('flights', 'bookings.flight_id', '=', 'flights.id')
            ->join('airlines', 'flights.airline_id', '=', 'airlines.id')
            ->where('bookings.status', 'confirmed')
            ->select('airlines.name', DB::raw('COUNT(*) as total_bookings'), DB::raw('SUM(flights.available_seats) as total_seats'))
            ->groupBy('airlines.name')
            ->get()
            ->map(function($item) {
                $item->occupancy = $item->total_seats > 0 ? round(($item->total_bookings / $item->total_seats) * 100, 1) : 0;
                return $item;
            });

        return view('manager.occupancy', compact('flights', 'airlineOccupancy'));
    }

    public function exportPdf()
    {
        $revenueByAirline = DB::table('bookings')
            ->join('flights', 'bookings.flight_id', '=', 'flights.id')
            ->join('airlines', 'flights.airline_id', '=', 'airlines.id')
            ->where('bookings.status', 'confirmed')
            ->select('airlines.name as airline_name', DB::raw('SUM(bookings.total_price) as total_revenue'), DB::raw('COUNT(*) as total_bookings'))
            ->groupBy('airlines.name')
            ->orderByDesc('total_revenue')
            ->get();

        $monthlyBookings = DB::table('bookings')
            ->where('status', 'confirmed')
            ->where('created_at', '>=', now()->subMonths(6))
            ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"), DB::raw('COUNT(*) as total'), DB::raw('SUM(total_price) as revenue'))
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
            ->orderBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
            ->get();

        $topRoutes = DB::table('bookings')
            ->join('flights', 'bookings.flight_id', '=', 'flights.id')
            ->join('airports as dep', 'flights.departure_airport_id', '=', 'dep.id')
            ->join('airports as arr', 'flights.arrival_airport_id', '=', 'arr.id')
            ->where('bookings.status', 'confirmed')
            ->select('dep.code as from_code', 'arr.code as to_code', DB::raw('COUNT(*) as total_bookings'))
            ->groupBy('dep.code', 'arr.code')
            ->orderByDesc('total_bookings')
            ->limit(5)
            ->get();

        $totalRevenue = Booking::where('status', 'confirmed')->sum('total_price');
        $totalBookings = Booking::where('status', 'confirmed')->count();
        $avgTicketPrice = $totalBookings > 0 ? $totalRevenue / $totalBookings : 0;

        $pdf = Pdf::loadView('manager.pdf-report', compact(
            'revenueByAirline', 'monthlyBookings', 'topRoutes', 'totalRevenue', 'totalBookings', 'avgTicketPrice'
        ));

        return $pdf->download('Laporan_Manajemen_' . date('Y-m-d') . '.pdf');
    }

    public function exportExcel()
    {
        $bookings = Booking::with(['flight.airline', 'user', 'passenger'])
            ->where('status', 'confirmed')
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = "Laporan_Booking_" . date('Y-m-d') . ".csv";
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($bookings) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No', 'Kode Booking', 'Tanggal', 'Customer', 'Email', 'Penerbangan', 'Maskapai', 'Penumpang', 'Total Harga', 'Status']);

            foreach ($bookings as $index => $booking) {
                fputcsv($file, [
                    $index + 1,
                    $booking->booking_code,
                    $booking->created_at->format('d/m/Y H:i'),
                    $booking->user->name ?? '-',
                    $booking->user->email ?? '-',
                    $booking->flight->flight_number ?? '-',
                    $booking->flight->airline->name ?? '-',
                    $booking->passenger->full_name ?? $booking->passenger->name ?? '-',
                    $booking->total_price,
                    $booking->status,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
