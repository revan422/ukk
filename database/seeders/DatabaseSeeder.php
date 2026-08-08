<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Users
        DB::table('users')->insertOrIgnore([
            [
                'name' => 'Admin Airline',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Test Customer',
                'email' => 'customer@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 2. Seed Airports
        DB::table('airports')->insertOrIgnore([
            ['id' => 1, 'name' => 'Soekarno-Hatta International Airport', 'code' => 'CGK', 'city' => 'Jakarta', 'country' => 'Indonesia', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Ngurah Rai International Airport', 'code' => 'DPS', 'city' => 'Bali', 'country' => 'Indonesia', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Juanda International Airport', 'code' => 'SUB', 'city' => 'Surabaya', 'country' => 'Indonesia', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Kualanamu International Airport', 'code' => 'KNO', 'city' => 'Medan', 'country' => 'Indonesia', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'name' => 'Yogyakarta International Airport', 'code' => 'YIA', 'city' => 'Yogyakarta', 'country' => 'Indonesia', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 3. Seed Airlines
        DB::table('airlines')->insertOrIgnore([
            ['id' => 1, 'name' => 'Garuda Indonesia', 'code' => 'GA', 'logo' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Lion Air', 'code' => 'JT', 'logo' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'AirAsia Indonesia', 'code' => 'QZ', 'logo' => null, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Batik Air', 'code' => 'ID', 'logo' => null, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 4. Seed Destinations
        DB::table('destinations')->insertOrIgnore([
            ['name' => 'Pulau Dewata Bali', 'city' => 'Denpasar', 'code' => 'DPS', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Metropolitan Jakarta', 'city' => 'Jakarta', 'code' => 'CGK', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kota Budaya Yogyakarta', 'city' => 'Yogyakarta', 'code' => 'YIA', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Keindahan Labuan Bajo', 'city' => 'Labuan Bajo', 'code' => 'LBJ', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 5. Seed Promos
        DB::table('promos')->insertOrIgnore([
            [
                'title' => 'Promo Tiket Murah 2026',
                'code' => 'DISKON2026',
                'description' => 'Potongan harga spesial untuk penerbangan domestik.',
                'discount_amount' => 100000.00,
                'start_date' => now()->subDays(5),
                'end_date' => now()->addMonths(6),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 6. Seed Flights
        DB::table('flights')->insertOrIgnore([
            [
                'airline_id' => 1,
                'departure_airport_id' => 1,
                'arrival_airport_id' => 2,
                'flight_number' => 'GA-101',
                'departure_time' => '2026-08-09 08:00:00',
                'arrival_time' => '2026-08-09 10:30:00',
                'flight_class' => 'economy',
                'status' => 'scheduled',
                'available_seats' => 45,
                'price' => 850000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'airline_id' => 2,
                'departure_airport_id' => 1,
                'arrival_airport_id' => 2,
                'flight_number' => 'JT-202',
                'departure_time' => '2026-08-09 13:00:00',
                'arrival_time' => '2026-08-09 15:30:00',
                'flight_class' => 'economy',
                'status' => 'scheduled',
                'available_seats' => 20,
                'price' => 550000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'airline_id' => 3,
                'departure_airport_id' => 2,
                'arrival_airport_id' => 3,
                'flight_number' => 'QZ-303',
                'departure_time' => '2026-08-10 09:00:00',
                'arrival_time' => '2026-08-10 10:00:00',
                'flight_class' => 'economy',
                'status' => 'scheduled',
                'available_seats' => 30,
                'price' => 650000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
