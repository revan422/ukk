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
        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('shipping_cost', 12, 2)->default(0.00)->after('total_price');
            $table->string('shipping_province')->nullable()->after('shipping_cost');
            $table->string('shipping_city')->nullable()->after('shipping_province');
            $table->text('shipping_address')->nullable()->after('shipping_city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['shipping_cost', 'shipping_province', 'shipping_city', 'shipping_address']);
        });
    }
};
