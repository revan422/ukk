<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Destination;
use App\Models\Promo;
use App\Models\Flight;
use App\Models\Airport;
use App\Models\Airline;
use Carbon\Carbon;

class LandingController extends Controller
{
    public function index()
    {
        // Data dari model yang sudah ada
        $destinations = Destination::all();
        $promos = Promo::where('end_date', '>=', now())->get();

        // Popular destinations dari airports (ambil 6 airport saja tanpa relasi)
        $popularDestinations = Airport::limit(6)->get();

        // Popular airlines (ambil 4 airline saja tanpa relasi)
        $popularAirlines = Airline::limit(4)->get();

        // Cheap flights (promo)
        $cheapFlights = Flight::with(['airline', 'departureAirport', 'arrivalAirport'])
            ->where('departure_time', '>=', Carbon::now())
            ->where('available_seats', '>', 0)
            ->orderBy('price', 'asc')
            ->limit(4)
            ->get();

        return view('landing.index', compact(
            'destinations',
            'promos',
            'popularDestinations',
            'popularAirlines',
            'cheapFlights'
        ));
    }

    public function searchFlights(Request $request)
    {
        return redirect()->route('flights.index');
    }
}
