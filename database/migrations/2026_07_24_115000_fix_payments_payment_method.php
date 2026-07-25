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
        // Ubah payment_method dari ENUM ke VARCHAR agar bisa menyimpan 'midtrans_snap'
        DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method VARCHAR(50) NOT NULL DEFAULT 'midtrans_snap'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke ENUM jika diperlukan
        DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method ENUM('midtrans_snap', 'bank_transfer', 'credit_card', 'cash') NOT NULL DEFAULT 'midtrans_snap'");
    }
};