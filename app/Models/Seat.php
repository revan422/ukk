<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    protected $fillable = ['flight_id', 'airplane_id', 'seat_number', 'seat_class', 'status', 'price'];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function flight() {
        return $this->belongsTo(Flight::class);
    }

    public function airplane() {
        return $this->belongsTo(Airplane::class);
    }
}
