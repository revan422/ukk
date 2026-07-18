<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    protected $fillable = ['name', 'code', 'city', 'country', 'address'];

    // Relasi: Bandara digunakan untuk keberangkatan dan kedatangan
    public function departures() {
        return $this->hasMany(Flight::class, 'departure_airport_id');
    }

    public function arrivals() {
        return $this->hasMany(Flight::class, 'arrival_airport_id');
    }

    // Relasi tambahan untuk LandingController
    public function departureFlights() {
        return $this->hasMany(Flight::class, 'departure_airport_id');
    }

    public function arrivalFlights() {
        return $this->hasMany(Flight::class, 'arrival_airport_id');
    }
}
