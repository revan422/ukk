<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Airline extends Model
{
    protected $fillable = ['name', 'code', 'logo', 'description'];

    // Relasi: Maskapai memiliki banyak pesawat & penerbangan
    public function airplanes() {
        return $this->hasMany(Airplane::class);
    }

    public function flights() {
        return $this->hasMany(Flight::class);
    }
}
