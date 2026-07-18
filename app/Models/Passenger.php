<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Passenger extends Model
{
protected $fillable = [
    'user_id',
    'full_name',
    'email',
    'phone',
    'id_card_number',
    'gender',
    'age',
    'date_of_birth',  // Tambahkan ini
];
    protected $casts = [
        'birth_date' => 'date',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function bookings() {
        return $this->hasMany(Booking::class);
    }
}
