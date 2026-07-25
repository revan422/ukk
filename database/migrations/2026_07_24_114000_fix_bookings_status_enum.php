<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah status dari ENUM ke VARCHAR agar tidak error case-insensitive
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status VARCHAR(50) NOT NULL DEFAULT 'UNPAID'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke ENUM
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('confirmed','on_time','delayed','cancelled','scheduled','available','booked') NOT NULL DEFAULT 'scheduled'");
    }
};