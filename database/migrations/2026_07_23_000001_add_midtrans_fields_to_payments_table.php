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
        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table) {
                if (!Schema::hasColumn('payments', 'snap_token')) {
                    $table->string('snap_token')->nullable()->after('transaction_id');
                }
                if (!Schema::hasColumn('payments', 'payment_type')) {
                    $table->string('payment_type')->nullable()->after('snap_token');
                }
                if (!Schema::hasColumn('payments', 'transaction_status')) {
                    $table->string('transaction_status')->nullable()->after('payment_type');
                }
                if (!Schema::hasColumn('payments', 'fraud_status')) {
                    $table->string('fraud_status')->nullable()->after('transaction_status');
                }
                if (!Schema::hasColumn('payments', 'expired_at')) {
                    $table->timestamp('expired_at')->nullable()->after('paid_at');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table) {
                $columnsToDrop = array_filter([
                    'snap_token',
                    'payment_type',
                    'transaction_status',
                    'fraud_status',
                    'expired_at',
                ], fn ($column) => Schema::hasColumn('payments', $column));

                if (!empty($columnsToDrop)) {
                    $table->dropColumn($columnsToDrop);
                }
            });
        }
    }
};
