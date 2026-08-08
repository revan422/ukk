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
        if (!Schema::hasTable('seats')) {
            Schema::create('seats', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('flight_id');
                $table->string('seat_number');
                $table->string('class')->default('economy');
                $table->boolean('is_available')->default(true);
                $table->decimal('price', 12, 2)->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
