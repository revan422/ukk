<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Daftarkan listener untuk event Registered
        // agar email verifikasi otomatis terkirim setelah register
        \Illuminate\Support\Facades\Event::listen(
            Registered::class,
            SendEmailVerificationNotification::class
        );
    }
}
