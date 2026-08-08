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
        if (!Schema::hasTable('flights')) {
            Schema::create('flights', function (Blueprint $table) {
                $table->id();
                $table->string('flight_number')->nullable();
                $table->dateTime('departure_time')->nullable();
                $table->integer('available_seats')->default(0);
                $table->decimal('price', 12, 2)->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
