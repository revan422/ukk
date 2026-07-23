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
            if (Schema::hasColumn('bookings', 'shipping_cost')) {
                $table->dropColumn(['shipping_cost']);
            }
            if (Schema::hasColumn('bookings', 'shipping_province')) {
                $table->dropColumn(['shipping_province']);
            }
            if (Schema::hasColumn('bookings', 'shipping_city')) {
                $table->dropColumn(['shipping_city']);
            }
            if (Schema::hasColumn('bookings', 'shipping_address')) {
                $table->dropColumn(['shipping_address']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'shipping_cost')) {
                $table->decimal('shipping_cost', 12, 2)->default(0.00)->after('total_price');
            }
            if (!Schema::hasColumn('bookings', 'shipping_province')) {
                $table->string('shipping_province')->nullable()->after('shipping_cost');
            }
            if (!Schema::hasColumn('bookings', 'shipping_city')) {
                $table->string('shipping_city')->nullable()->after('shipping_province');
            }
            if (!Schema::hasColumn('bookings', 'shipping_address')) {
                $table->text('shipping_address')->nullable()->after('shipping_city');
            }
        });
    }
};
