<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name', 'email', 'password', 'role',
    'phone', 'gender', 'date_of_birth',
    'passport_number', 'passport_expiry', 'passport_country',
    'frequent_flyer_number', 'loyalty_points', 'loyalty_tier',
    'favorite_seat', 'meal_preference', 'travel_companions',
    'payment_methods', 'two_factor_enabled'
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'passport_expiry' => 'date',
            'date_of_birth' => 'date',
            'travel_companions' => 'array',
            'payment_methods' => 'array',
            'two_factor_enabled' => 'boolean',
        ];
    }

    /**
     * Relasi: User memiliki banyak booking
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Relasi: User memiliki banyak passenger
     */
    public function passengers()
    {
        return $this->hasMany(Passenger::class);
    }

    /**
     * Kirim notifikasi reset password menggunakan notifikasi bawaan Laravel.
     * Ini akan mengirim email dengan template HTML yang sudah disediakan Laravel.
     */
    public function sendPasswordResetNotification($token)
    {
        // Gunakan notifikasi bawaan Laravel (mengirim email HTML dengan tombol reset)
        $this->notify(new \Illuminate\Auth\Notifications\ResetPassword($token));
    }
}
