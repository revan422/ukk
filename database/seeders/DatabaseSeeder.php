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
            ['name' => 'Soekarno-Hatta International Airport', 'code' => 'CGK', 'city' => 'Jakarta', 'country' => 'Indonesia', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ngurah Rai International Airport', 'code' => 'DPS', 'city' => 'Bali', 'country' => 'Indonesia', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Juanda International Airport', 'code' => 'SUB', 'city' => 'Surabaya', 'country' => 'Indonesia', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kualanamu International Airport', 'code' => 'KNO', 'city' => 'Medan', 'country' => 'Indonesia', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Yogyakarta International Airport', 'code' => 'YIA', 'city' => 'Yogyakarta', 'country' => 'Indonesia', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 3. Seed Airlines
        DB::table('airlines')->insertOrIgnore([
            ['name' => 'Garuda Indonesia', 'code' => 'GA', 'logo' => null, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lion Air', 'code' => 'JT', 'logo' => null, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'AirAsia Indonesia', 'code' => 'QZ', 'logo' => null, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Batik Air', 'code' => 'ID', 'logo' => null, 'created_at' => now(), 'updated_at' => now()],
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
                'flight_number' => 'GA-101',
                'departure_time' => now()->addDays(2),
                'available_seats' => 45,
                'price' => 850000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'flight_number' => 'JT-202',
                'departure_time' => now()->addDays(3),
                'available_seats' => 20,
                'price' => 550000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'flight_number' => 'QZ-303',
                'departure_time' => now()->addDays(5),
                'available_seats' => 30,
                'price' => 650000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
