<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Airplane extends Model
{
    protected $fillable = ['airline_id', 'model', 'registration_number', 'capacity', 'description', 'photo'];

    // Relasi
    public function airline() {
        return $this->belongsTo(Airline::class);
    }

    public function flights() {
        return $this->hasMany(Flight::class);
    }

    public function seats() {
        return $this->hasMany(Seat::class);
    }
}
