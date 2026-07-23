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
        Schema::table('payments', function (Blueprint $table) {
            $table->string('snap_token')->nullable()->after('transaction_id');
            $table->string('payment_type')->nullable()->after('snap_token');
            $table->string('transaction_status')->nullable()->after('payment_type');
            $table->string('fraud_status')->nullable()->after('transaction_status');
            $table->timestamp('expired_at')->nullable()->after('paid_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'snap_token',
                'payment_type',
                'transaction_status',
                'fraud_status',
                'expired_at',
            ]);
        });
    }
};
