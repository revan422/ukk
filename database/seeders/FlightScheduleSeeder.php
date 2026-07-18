<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Flight;
use App\Models\Seat;
use Carbon\Carbon;

class FlightScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus flight dan seat lama (opsional)
        // Flight::truncate();
        // Seat::truncate();

        // Data rute yang akan digunakan
        $routes = [
            ['departure' => 1, 'arrival' => 2], // CGK -> SUB
            ['departure' => 2, 'arrival' => 1], // SUB -> CGK
            ['departure' => 1, 'arrival' => 3], // CGK -> DPS
            ['departure' => 3, 'arrival' => 1], // DPS -> CGK
            ['departure' => 1, 'arrival' => 4], // CGK -> KNO
            ['departure' => 4, 'arrival' => 1], // KNO -> CGK
            ['departure' => 2, 'arrival' => 3], // SUB -> DPS
            ['departure' => 3, 'arrival' => 2], // DPS -> SUB
        ];

        // Maskapai dan pesawat
        $airlines = [
            ['airline_id' => 1, 'airplane_id' => 1, 'code' => 'GA'], // Garuda
            ['airline_id' => 1, 'airplane_id' => 2, 'code' => 'GA'], // Garuda
            ['airline_id' => 2, 'airplane_id' => 3, 'code' => 'JT'], // Lion Air
            ['airline_id' => 3, 'airplane_id' => 4, 'code' => 'QZ'], // AirAsia
        ];

        // Generate jadwal untuk 90 hari ke depan (3 bulan)
        $daysToGenerate = 90;
        $startDate = Carbon::tomorrow(); // Mulai dari besok

        $flightNumber = 100;

        for ($day = 0; $day < $daysToGenerate; $day++) {
            $currentDate = $startDate->copy()->addDays($day);

            // Setiap hari ada 3-4 penerbangan per rute
            $flightsPerDay = 4;

            for ($i = 0; $i < $flightsPerDay; $i++) {
                foreach ($routes as $routeIndex => $route) {
                    // Pilih maskapai secara bergantian
                    $airlineData = $airlines[$routeIndex % count($airlines)];

                    // Tentukan jam keberangkatan (pagi, siang, sore, malam)
                    $departureHours = [6, 10, 14, 18];
                    $departureHour = $departureHours[$i];

                    // Tambahkan variasi menit
                    $departureMinute = ($routeIndex * 15) % 60;

                    $departureTime = $currentDate->copy()->setTime($departureHour, $departureMinute);
                    $arrivalTime = $departureTime->copy()->addHours(2); // Durasi 2 jam

                    // Harga berbeda berdasarkan waktu (malam lebih murah)
                    $basePrice = 750000;
                    if ($departureHour < 8) {
                        $basePrice = 850000; // Pagi lebih mahal
                    } elseif ($departureHour >= 18) {
                        $basePrice = 650000; // Malam lebih murah
                    }

                    // Buat flight
                    $flight = Flight::create([
                        'airline_id' => $airlineData['airline_id'],
                        'airplane_id' => $airlineData['airplane_id'],
                        'departure_airport_id' => $route['departure'],
                        'arrival_airport_id' => $route['arrival'],
                        'flight_number' => $airlineData['code'] . sprintf('%04d', $flightNumber),
                        'departure_time' => $departureTime,
                        'arrival_time' => $arrivalTime,
                        'price' => $basePrice,
                        'available_seats' => 150,
                        'status' => 'scheduled',
                    ]);

                    $flightNumber++;

                    // Buat kursi untuk flight ini (18 kursi)
                    $this->createSeatsForFlight($flight);
                }
            }
        }

        $this->command->info("✅ Berhasil generate jadwal penerbangan untuk {$daysToGenerate} hari ke depan!");
        $this->command->info("📊 Total flight dibuat: " . Flight::count());
    }

    /**
     * Buat kursi untuk setiap flight
     */
    private function createSeatsForFlight($flight)
    {
        $seatNumbers = [
            '1A', '1B', '1C', '1D', '1E', '1F',
            '2A', '2B', '2C', '2D', '2E', '2F',
            '3A', '3B', '3C', '3D', '3E', '3F',
        ];

        foreach ($seatNumbers as $index => $seatNumber) {
            // Baris 1-2 = Business Class, sisanya Economy
            $rowNumber = floor($index / 6) + 1;
            $seatClass = ($rowNumber <= 2) ? 'business' : 'economy';

            // Harga berbeda untuk business class
            $price = ($seatClass === 'business') ? 1500000 : $flight->price;

            Seat::create([
                'flight_id' => $flight->id,
                'airplane_id' => $flight->airplane_id,
                'seat_number' => $seatNumber,
                'seat_class' => $seatClass,
                'status' => 'available',
                'price' => $price,
            ]);
        }
    }
}
