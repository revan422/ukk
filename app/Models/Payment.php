<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'booking_id', 'payment_method', 'amount', 'payment_status',
        'transaction_id', 'payment_proof', 'paid_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function booking() {
        return $this->belongsTo(Booking::class);
    }
}
