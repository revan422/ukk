<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Airport;
use App\Models\Airline;
use App\Models\Airplane;
use App\Models\Flight;
use App\Models\Seat;
use Carbon\Carbon;

class FlightDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info("🚀 Starting seeding...");

        // 1. AIRLINES
        $airlinesData = [
            ['id' => 1, 'name' => 'Garuda Indonesia', 'code' => 'GA'],
            ['id' => 2, 'name' => 'Lion Air', 'code' => 'JT'],
            ['id' => 3, 'name' => 'AirAsia', 'code' => 'QZ'],
        ];

        foreach ($airlinesData as $airline) {
            Airline::updateOrCreate(['id' => $airline['id']], $airline);
        }
        $this->command->info("✅ Airlines created: " . Airline::count());

        // 2. AIRPLANES
        $airplanesData = [
            ['id' => 1, 'airline_id' => 1, 'model' => 'Boeing 737-800', 'registration_number' => 'PK-GFN', 'capacity' => 180],
            ['id' => 2, 'airline_id' => 2, 'model' => 'Boeing 737-900ER', 'registration_number' => 'PK-LFP', 'capacity' => 180],
            ['id' => 3, 'airline_id' => 3, 'model' => 'Airbus A320', 'registration_number' => 'PK-AZC', 'capacity' => 180],
        ];

        foreach ($airplanesData as $airplane) {
            Airplane::updateOrCreate(['id' => $airplane['id']], $airplane);
        }
        $this->command->info("✅ Airplanes created: " . Airplane::count());

        // 3. AIRPORTS
        $airports = [
            ['code' => 'CGK', 'name' => 'Soekarno-Hatta Airport', 'city' => 'Jakarta', 'country' => 'Indonesia'],
            ['code' => 'SUB', 'name' => 'Juanda Airport', 'city' => 'Surabaya', 'country' => 'Indonesia'],
            ['code' => 'DPS', 'name' => 'Ngurah Rai Airport', 'city' => 'Bali', 'country' => 'Indonesia'],
            ['code' => 'KNO', 'name' => 'Kualanamu Airport', 'city' => 'Medan', 'country' => 'Indonesia'],
            ['code' => 'UPG', 'name' => 'Hasanuddin Airport', 'city' => 'Makassar', 'country' => 'Indonesia'],
            ['code' => 'SIN', 'name' => 'Changi Airport', 'city' => 'Singapore', 'country' => 'Singapore'],
            ['code' => 'KUL', 'name' => 'KLIA Airport', 'city' => 'Kuala Lumpur', 'country' => 'Malaysia'],
            ['code' => 'BKK', 'name' => 'Suvarnabhumi Airport', 'city' => 'Bangkok', 'country' => 'Thailand'],
        ];

        foreach ($airports as $airport) {
            Airport::updateOrCreate(['code' => $airport['code']], $airport);
        }
        $this->command->info("✅ Airports created: " . Airport::count());

        // 4. RUTES
        $routes = [
            ['from' => 'CGK', 'to' => 'SUB', 'duration' => 90, 'basePrice' => 800000],
            ['from' => 'CGK', 'to' => 'DPS', 'duration' => 105, 'basePrice' => 1200000],
            ['from' => 'CGK', 'to' => 'KNO', 'duration' => 120, 'basePrice' => 1000000],
            ['from' => 'CGK', 'to' => 'UPG', 'duration' => 135, 'basePrice' => 1500000],
            ['from' => 'CGK', 'to' => 'SIN', 'duration' => 90, 'basePrice' => 2500000],
            ['from' => 'CGK', 'to' => 'KUL', 'duration' => 120, 'basePrice' => 2000000],
            ['from' => 'CGK', 'to' => 'BKK', 'duration' => 180, 'basePrice' => 3500000],
            ['from' => 'SUB', 'to' => 'DPS', 'duration' => 60, 'basePrice' => 700000],
            ['from' => 'DPS', 'to' => 'SIN', 'duration' => 120, 'basePrice' => 2000000],
            ['from' => 'SIN', 'to' => 'KUL', 'duration' => 60, 'basePrice' => 800000],
        ];

        // 5. GENERATE FLIGHTS
        $airlines = Airline::all();
        $airplanes = Airplane::all();
        $startDate = Carbon::tomorrow();
        $flightNumber = 1000;

        $departureTimes = ['06:00', '10:00', '14:00', '18:00'];

        $this->command->info("⏳ Generating flights for 7 days...");

        for ($day = 0; $day < 7; $day++) {
            $currentDate = $startDate->copy()->addDays($day);

            foreach ($routes as $routeIndex => $route) {
                $airline = $airlines->get($routeIndex % 3);
                $airplane = $airplanes->get($routeIndex % 3);

                $departureTime = Carbon::parse($currentDate->format('Y-m-d') . ' ' . $departureTimes[$routeIndex % 4]);
                $arrivalTime = $departureTime->copy()->addMinutes($route['duration']);

                $depAirport = Airport::where('code', $route['from'])->first();
                $arrAirport = Airport::where('code', $route['to'])->first();

                if (!$depAirport || !$arrAirport) continue;

                // Economy Class
                $flight = Flight::create([
                    'airline_id' => $airline->id,
                    'airplane_id' => $airplane->id,
                    'departure_airport_id' => $depAirport->id,
                    'arrival_airport_id' => $arrAirport->id,
                    'flight_number' => $airline->code . sprintf('%04d', $flightNumber),
                    'departure_time' => $departureTime,
                    'arrival_time' => $arrivalTime,
                    'price' => $route['basePrice'],
                    'flight_class' => 'economy',
                    'total_seats' => 30,
                    'available_seats' => 30,
                    'status' => 'scheduled',
                ]);

                // Create seats for economy
                $this->createSeats($flight, 'economy', $route['basePrice'], range(7, 12));

                // Business Class untuk rute >= 2 jam
                if ($route['duration'] >= 120) {
                    $businessFlight = Flight::create([
                        'airline_id' => $airline->id,
                        'airplane_id' => $airplane->id,
                        'departure_airport_id' => $depAirport->id,
                        'arrival_airport_id' => $arrAirport->id,
                        'flight_number' => $airline->code . sprintf('%04d', $flightNumber + 100),
                        'departure_time' => $departureTime,
                        'arrival_time' => $arrivalTime,
                        'price' => $route['basePrice'] * 2.5,
                        'flight_class' => 'business',
                        'total_seats' => 12,
                        'available_seats' => 12,
                        'status' => 'scheduled',
                    ]);

                    $this->createSeats($businessFlight, 'business', $route['basePrice'] * 2.5, range(3, 5));
                }

                // First Class untuk rute internasional jauh
                if (in_array($route['to'], ['SIN', 'KUL', 'BKK'])) {
                    $firstFlight = Flight::create([
                        'airline_id' => $airline->id,
                        'airplane_id' => $airplane->id,
                        'departure_airport_id' => $depAirport->id,
                        'arrival_airport_id' => $arrAirport->id,
                        'flight_number' => $airline->code . sprintf('%04d', $flightNumber + 200),
                        'departure_time' => $departureTime,
                        'arrival_time' => $arrivalTime,
                        'price' => $route['basePrice'] * 5,
                        'flight_class' => 'first',
                        'total_seats' => 8,
                        'available_seats' => 8,
                        'status' => 'scheduled',
                    ]);

                    $this->createSeats($firstFlight, 'first', $route['basePrice'] * 5, range(1, 2));
                }

                $flightNumber++;
            }
        }

        $this->command->info("✅ Seeding completed!");
        $this->command->info("📊 Total flights: " . Flight::count());
        $this->command->info("📊 Total seats: " . Seat::count());
    }

    private function createSeats($flight, $class, $price, $rows)
    {
        $seatLetters = ['A', 'B', 'C', 'D', 'E', 'F'];

        foreach ($rows as $row) {
            foreach ($seatLetters as $letter) {
                Seat::create([
                    'flight_id' => $flight->id,
                    'airplane_id' => $flight->airplane_id,
                    'seat_number' => $row . $letter,
                    'seat_class' => $class,
                    'status' => 'available',
                    'price' => $price,
                ]);
            }
        }
    }
}
