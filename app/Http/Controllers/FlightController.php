<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Flight;
use App\Models\Seat;
use App\Models\Airport;
use App\Models\Airline;
use App\Models\Airplane;
use Carbon\Carbon;

class FlightController extends Controller
{
    public function index()
    {
        return view('flights.index');
    }

    public function search(Request $request)
    {
        $request->validate([
            'departure' => 'required|exists:airports,id',
            'arrival' => 'required|exists:airports,id',
            'date' => 'required|date',
            'class' => 'required|in:economy,business,first',
        ]);

        $query = Flight::with(['airline', 'departureAirport', 'arrivalAirport', 'seats'])
            ->where('departure_airport_id', $request->departure)
            ->where('arrival_airport_id', $request->arrival)
            ->whereDate('departure_time', $request->date)
            ->where('status', 'scheduled')
            ->where('available_seats', '>', 0)
            ->where('flight_class', $request->class);

        $flight = $query->orderBy('departure_time')->first();

        // Jika tidak ada penerbangan, auto-generate untuk 15 hari ke depan (sekarang beserta kursi)
        if (!$flight) {
            $this->generateFlightsForRoute(
                $request->departure,
                $request->arrival,
                $request->date
            );

            // Cari lagi setelah generate
            $flight = Flight::with(['airline', 'departureAirport', 'arrivalAirport', 'seats'])
                ->where('departure_airport_id', $request->departure)
                ->where('arrival_airport_id', $request->arrival)
                ->whereDate('departure_time', '>=', $request->date)
                ->whereDate('departure_time', '<=', Carbon::parse($request->date)->addDays(14))
                ->where('status', 'scheduled')
                ->where('available_seats', '>', 0)
                ->where('flight_class', $request->class)
                ->orderBy('departure_time')
                ->first();
        }

        // Jika masih tidak ada, cari tanpa filter kelas (mungkin kelas berbeda)
        if (!$flight) {
            $flight = Flight::with(['airline', 'departureAirport', 'arrivalAirport', 'seats'])
                ->where('departure_airport_id', $request->departure)
                ->where('arrival_airport_id', $request->arrival)
                ->whereDate('departure_time', '>=', $request->date)
                ->whereDate('departure_time', '<=', Carbon::parse($request->date)->addDays(14))
                ->where('status', 'scheduled')
                ->where('available_seats', '>', 0)
                ->orderBy('departure_time')
                ->first();
        }

        // Langsung redirect ke halaman pilih kursi
        if ($flight) {
            return redirect()->route('bookings.selectSeat', $flight->id);
        }

        // Jika benar-benar tidak ada, redirect balik dengan pesan error
        return redirect()->route('flights.index')
            ->with('error', 'Maaf, tidak ada penerbangan tersedia untuk rute dan tanggal yang dipilih. Silakan coba rute atau tanggal lain.')
            ->withInput();
    }

    /**
     * Generate flights for a route for 15 days ahead (all 3 classes) beserta kursi
     */
    private function generateFlightsForRoute($departureAirportId, $arrivalAirportId, $startDate)
    {
        $startDate = Carbon::parse($startDate);
        $airlines = Airline::all();
        $airplanes = Airplane::all();

        if ($airlines->isEmpty() || $airplanes->isEmpty()) {
            return;
        }

        // Cek apakah sudah ada penerbangan untuk rute ini di 15 hari ke depan
        $existingFlights = Flight::where('departure_airport_id', $departureAirportId)
            ->where('arrival_airport_id', $arrivalAirportId)
            ->whereDate('departure_time', '>=', $startDate)
            ->whereDate('departure_time', '<=', (clone $startDate)->addDays(14))
            ->get()
            ->groupBy(function ($flight) {
                return $flight->departure_time->format('Y-m-d') . '_' . $flight->flight_class;
            });

        $classes = ['economy', 'business', 'first'];
        $classConfig = [
            'economy' => [
                'price_min' => 500000,
                'price_max' => 1500000,
                'seats' => 120,
                'duration_minutes' => [60, 180],
                'seat_rows' => ['range' => [1, 20], 'letters' => ['A', 'B', 'C', 'D', 'E', 'F']],
                'seat_letters' => ['A', 'B', 'C', 'D', 'E', 'F'],
            ],
            'business' => [
                'price_min' => 2000000,
                'price_max' => 5000000,
                'seats' => 40,
                'duration_minutes' => [60, 180],
                'seat_rows' => ['range' => [21, 27], 'letters' => ['A', 'B', 'C', 'D']],
                'seat_letters' => ['A', 'B', 'C', 'D'],
            ],
            'first' => [
                'price_min' => 5000000,
                'price_max' => 15000000,
                'seats' => 20,
                'duration_minutes' => [60, 180],
                'seat_rows' => ['range' => [28, 31], 'letters' => ['A', 'B', 'C', 'D']],
                'seat_letters' => ['A', 'B', 'C', 'D'],
            ],
        ];

        $departureTimes = ['06:00', '08:00', '10:00', '12:00', '14:00', '16:00', '18:00', '20:00'];

        for ($day = 0; $day < 15; $day++) {
            $currentDate = (clone $startDate)->addDays($day);

            foreach ($classes as $class) {
                $key = $currentDate->format('Y-m-d') . '_' . $class;

                // Skip if already has flights for this class on this date
                if (isset($existingFlights[$key]) && $existingFlights[$key]->count() > 0) {
                    continue;
                }

                // Pilih random airline & airplane
                $airline = $airlines->random();
                $airplane = $airplanes->random();

                // Pilih random departure time
                $depTime = $departureTimes[array_rand($departureTimes)];
                $departureDateTime = (clone $currentDate)->setTime(
                    (int)explode(':', $depTime)[0],
                    (int)explode(':', $depTime)[1]
                );

                // Hitung durasi penerbangan (random antara 1-3 jam)
                $durationMinutes = rand(
                    $classConfig[$class]['duration_minutes'][0],
                    $classConfig[$class]['duration_minutes'][1]
                );
                $arrivalDateTime = (clone $departureDateTime)->addMinutes($durationMinutes);

                // Harga random sesuai kelas
                $price = rand(
                    $classConfig[$class]['price_min'],
                    $classConfig[$class]['price_max']
                );

                // Generate flight number
                $flightNumber = $airline->code . '-' . rand(100, 999);

                // Hitung kapasitas kursi berdasarkan konfigurasi kelas
                $totalSeats = $classConfig[$class]['seats'];

                $flight = Flight::create([
                    'airline_id' => $airline->id,
                    'airplane_id' => $airplane->id,
                    'departure_airport_id' => $departureAirportId,
                    'arrival_airport_id' => $arrivalAirportId,
                    'flight_number' => $flightNumber,
                    'departure_time' => $departureDateTime,
                    'arrival_time' => $arrivalDateTime,
                    'price' => $price,
                    'flight_class' => $class,
                    'total_seats' => $totalSeats,
                    'available_seats' => $totalSeats,
                    'status' => 'scheduled',
                ]);

                // Generate kursi kosong untuk penerbangan ini
                $this->generateSeatsForFlight($flight, $class, $classConfig[$class], $price);
            }
        }
    }

    /**
     * Generate kursi kosong untuk sebuah penerbangan
     */
    private function generateSeatsForFlight($flight, $class, $config, $basePrice)
    {
        $seatLetters = $config['seat_letters'];
        $rowStart = $config['seat_rows']['range'][0];
        $rowEnd = $config['seat_rows']['range'][1];

        $seatNumber = 1;
        for ($row = $rowStart; $row <= $rowEnd; $row++) {
            foreach ($seatLetters as $letter) {
                $seatLabel = $row . $letter;

                // Harga per kursi: base price + variasi kecil per kursi
                $seatPrice = $basePrice + rand(-50000, 50000);

                Seat::create([
                    'flight_id' => $flight->id,
                    'airplane_id' => $flight->airplane_id,
                    'seat_number' => $seatLabel,
                    'seat_class' => $class,
                    'status' => 'available',
                    'price' => max(100000, $seatPrice),
                ]);

                $seatNumber++;
                if ($seatNumber > $config['seats']) {
                    break 2;
                }
            }
        }
    }
}
