<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;

// Halaman Landing Page
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Routes Authentication (BISA DIAKSES TANPA LOGIN)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/login/admin', [AuthController::class, 'showLogin'])->name('admin.login');
Route::get('/login/staff', [AuthController::class, 'showLogin'])->name('staff.login');
Route::get('/login/manager', [AuthController::class, 'showLogin'])->name('manager.login');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Register khusus per role (TANPA LOGIN) - TIDAK ADA CS
Route::get('/admin/register', [AuthController::class, 'showRegister'])->name('admin.register');
Route::get('/customer/register', [AuthController::class, 'showRegister'])->name('customer.register');
Route::get('/staff/register', [AuthController::class, 'showRegister'])->name('staff.register');
Route::get('/manager/register', [AuthController::class, 'showRegister'])->name('manager.register');

// Register default untuk pelanggan/customer
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');

// POST route untuk proses register
Route::post('/register', [AuthController::class, 'register']);

// Routes Forgot & Reset Password
Route::get('/forgot-password', [AuthController::class, 'showLinkRequestForm'])->middleware('guest')->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->middleware('guest')->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->middleware('guest')->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('guest')->name('password.update');

// Routes AJAX Captcha Verification
Route::post('/captcha/verify-checkbox', [AuthController::class, 'verifyCheckboxCaptcha'])->name('captcha.verifyCheckbox');

// Routes RajaOngkir Komerce API
Route::get('/rajaongkir/provinces', function (\App\Services\RajaOngkirService $service) {
    return response()->json($service->getProvinces());
})->name('rajaongkir.provinces');

Route::get('/rajaongkir/destinations', function (\Illuminate\Http\Request $request, \App\Services\RajaOngkirService $service) {
    $request->validate(['search' => 'required|string|min:3']);
    return response()->json($service->getDomesticDestination($request->search));
})->name('rajaongkir.destinations');

Route::post('/rajaongkir/calculate-cost', function (\Illuminate\Http\Request $request, \App\Services\RajaOngkirService $service) {
    $request->validate([
        'origin'      => 'required',
        'destination' => 'required',
        'weight'      => 'required|integer|min:1',
        'courier'     => 'required|string',
        'service'     => 'nullable|string',
    ]);
    return response()->json($service->calculateDomesticCost(
        $request->origin,
        $request->destination,
        $request->weight,
        $request->courier,
        $request->service ?? 'lowest'
    ));
})->name('rajaongkir.calculate-cost');

Route::get('/tes-rajaongkir', function () {

    $response = Http::timeout(10)
        ->withHeaders([
            'key' => env('RAJAONGKIR_API_KEY'),
        ])
        ->get(
            env('RAJAONGKIR_BASE_URL') . '/destination/domestic-destination',
            [
                'search' => 'Cicadas Ciampea Bogor'
            ]
        );

    return response()->json([
        'status' => $response->status(),
        'body' => $response->json(),
    ]);

});

// =========================
// TEST HITUNG ONGKIR
// ===

Route::get('/tes-ongkir', function () {

    $response = Http::withHeaders([
        'key' => env('RAJAONGKIR_API_KEY'),
    ])->post(
        env('RAJAONGKIR_BASE_URL') . '/calculate/domestic-cost',
        [
            'origin' => env('RAJAONGKIR_ORIGIN'),
            'destination' => 8118,
            'weight' => 1000,
            'courier' => 'pos',
            'price' => 'lowest'
        ]
    );

    return response()->json($response->json());

});

// Routes Email Verification (menggunakan fitur bawaan Laravel)
Route::get('/email/verify', function () {
    return view('auth.verify');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Http\Request $request, $id, $hash) {
    $user = \App\Models\User::findOrFail($id);

    // Verify the email hash
    if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        abort(403, 'Tautan verifikasi tidak valid.');
    }

    if (!$user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new \Illuminate\Auth\Events\Verified($user));
    }

    // Redirect ke halaman login dengan pesan sukses (TIDAK auto-login)
    return redirect()->route('login')->with('success', 'Email Anda telah berhasil diverifikasi! Silakan login.');
})->middleware(['signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (\Illuminate\Http\Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Link verifikasi baru telah dikirim!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.resend');

// Routes Pencarian Penerbangan (Bisa diakses tanpa login)
Route::get('/flights', [FlightController::class, 'index'])->name('flights.index');
Route::get('/flights/search', [FlightController::class, 'search'])->name('flights.search');

// Routes Customer (WAJIB Login + Verifikasi Email)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/bookings/history', [BookingController::class, 'history'])->name('bookings.history');
    Route::get('/bookings/select-seat/{flightId}', [BookingController::class, 'selectSeat'])->name('bookings.selectSeat');
    Route::post('/bookings/process-seat', [BookingController::class, 'processSeat'])->name('bookings.processSeat');
    Route::get('/bookings/passenger', [BookingController::class, 'passengerForm'])->name('bookings.passenger');
    Route::post('/bookings/process-passenger', [BookingController::class, 'processPassenger'])->name('bookings.processPassenger');
    Route::get('/bookings/confirmation', [BookingController::class, 'confirmation'])->name('bookings.confirmation');
    Route::post('/bookings/process-payment', [BookingController::class, 'processPayment'])->name('bookings.processPayment');
    Route::get('/bookings/success/{bookingId}', [BookingController::class, 'success'])->name('bookings.success');

    // Routes Profile Settings
    Route::get('/profile/settings', [ProfileController::class, 'index'])->name('profile.settings');
    Route::post('/profile/settings/update', [ProfileController::class, 'update'])->name('profile.settings.update');
    Route::post('/profile/settings/security', [ProfileController::class, 'updateSecurity'])->name('profile.settings.security');
});

// Routes ADMIN (WAJIB Login + Role Admin + Verifikasi Email)
Route::middleware(['auth', 'admin', 'verified'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');

    // Users CRUD
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/admin/users/create', [AdminController::class, 'usersCreate'])->name('admin.users.create');
    Route::post('/admin/users', [AdminController::class, 'usersStore'])->name('admin.users.store');
    Route::get('/admin/users/{id}/edit', [AdminController::class, 'usersEdit'])->name('admin.users.edit');
    Route::put('/admin/users/{id}', [AdminController::class, 'usersUpdate'])->name('admin.users.update');
    Route::delete('/admin/users/{id}', [AdminController::class, 'usersDelete'])->name('admin.users.delete');

    // Flights CRUD
    Route::get('/admin/flights', [AdminController::class, 'flights'])->name('admin.flights');
    Route::get('/admin/flights/create', [AdminController::class, 'flightsCreate'])->name('admin.flights.create');
    Route::post('/admin/flights', [AdminController::class, 'flightsStore'])->name('admin.flights.store');
    Route::get('/admin/flights/{id}/edit', [AdminController::class, 'flightsEdit'])->name('admin.flights.edit');
    Route::put('/admin/flights/{id}', [AdminController::class, 'flightsUpdate'])->name('admin.flights.update');
    Route::delete('/admin/flights/{id}', [AdminController::class, 'flightsDelete'])->name('admin.flights.delete');

    // Airlines CRUD
    Route::get('/admin/airlines', [AdminController::class, 'airlines'])->name('admin.airlines');
    Route::post('/admin/airlines', [AdminController::class, 'airlinesStore'])->name('admin.airlines.store');
    Route::put('/admin/airlines/{id}', [AdminController::class, 'airlinesUpdate'])->name('admin.airlines.update');
    Route::delete('/admin/airlines/{id}', [AdminController::class, 'airlinesDelete'])->name('admin.airlines.delete');

    // Airports CRUD
    Route::get('/admin/airports', [AdminController::class, 'airports'])->name('admin.airports');
    Route::post('/admin/airports', [AdminController::class, 'airportsStore'])->name('admin.airports.store');
    Route::put('/admin/airports/{id}', [AdminController::class, 'airportsUpdate'])->name('admin.airports.update');
    Route::delete('/admin/airports/{id}', [AdminController::class, 'airportsDelete'])->name('admin.airports.delete');

    // Airplanes CRUD
    Route::get('/admin/airplanes', [AdminController::class, 'airplanes'])->name('admin.airplanes');
    Route::post('/admin/airplanes', [AdminController::class, 'airplanesStore'])->name('admin.airplanes.store');
    Route::put('/admin/airplanes/{id}', [AdminController::class, 'airplanesUpdate'])->name('admin.airplanes.update');
    Route::delete('/admin/airplanes/{id}', [AdminController::class, 'airplanesDelete'])->name('admin.airplanes.delete');

    // Bookings
    Route::get('/admin/bookings', [AdminController::class, 'bookings'])->name('admin.bookings');
});

// Routes MANAGER (WAJIB Login + Role Manager + Verifikasi Email)
Route::middleware(['auth', 'manager', 'verified'])->group(function () {
    Route::get('/manager', [ManagerController::class, 'index'])->name('manager.dashboard');
    Route::get('/manager/reports', [ManagerController::class, 'reports'])->name('manager.reports');
    Route::get('/manager/performance', [ManagerController::class, 'performance'])->name('manager.performance');
    Route::get('/manager/export-pdf', [ManagerController::class, 'exportPdf'])->name('manager.export-pdf');
    Route::get('/manager/export-excel', [ManagerController::class, 'exportExcel'])->name('manager.export-excel');
    Route::get('/manager/revenue', [ManagerController::class, 'revenueAnalytics'])->name('manager.revenue');
    Route::get('/manager/occupancy', [ManagerController::class, 'occupancyRate'])->name('manager.occupancy');
});

// Routes STAFF (WAJIB Login + Role Staff + Verifikasi Email)
Route::middleware(['auth', 'staff', 'verified'])->group(function () {
    Route::get('/staff', [StaffController::class, 'index'])->name('staff.dashboard');
    Route::get('/staff/bookings', [StaffController::class, 'bookings'])->name('staff.bookings');
    Route::post('/staff/bookings/{bookingId}/reschedule', [StaffController::class, 'reschedule'])->name('staff.reschedule');
    Route::post('/staff/bookings/{bookingId}/cancel', [StaffController::class, 'cancel'])->name('staff.cancel');
    Route::get('/staff/complaints', [StaffController::class, 'complaints'])->name('staff.complaints');
    Route::get('/staff/manifest/{flightId}', [StaffController::class, 'manifest'])->name('staff.manifest');
    Route::get('/staff/flights', [StaffController::class, 'flightMonitoring'])->name('staff.flights');
});
