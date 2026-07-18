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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('gender')->nullable()->after('phone');
            $table->date('date_of_birth')->nullable()->after('gender');
            $table->string('passport_number')->nullable()->after('date_of_birth');
            $table->date('passport_expiry')->nullable()->after('passport_number');
            $table->string('passport_country')->nullable()->after('passport_expiry');
            $table->string('frequent_flyer_number')->nullable()->after('passport_country');
            $table->integer('loyalty_points')->default(2500)->after('frequent_flyer_number');
            $table->string('loyalty_tier')->default('Bronze')->after('loyalty_points');
            $table->string('favorite_seat')->nullable()->after('loyalty_tier');
            $table->string('meal_preference')->nullable()->after('favorite_seat');
            $table->json('travel_companions')->nullable()->after('meal_preference');
            $table->json('payment_methods')->nullable()->after('travel_companions');
            $table->boolean('two_factor_enabled')->default(false)->after('payment_methods');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone', 'gender', 'date_of_birth',
                'passport_number', 'passport_expiry', 'passport_country',
                'frequent_flyer_number', 'loyalty_points', 'loyalty_tier',
                'favorite_seat', 'meal_preference', 'travel_companions',
                'payment_methods', 'two_factor_enabled'
            ]);
        });
    }
};
