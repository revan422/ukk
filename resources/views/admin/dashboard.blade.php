@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('panel_title', 'Admin Panel')
@section('role_name', 'Administrator')
@section('role_badge', 'Admin')

@section('sidebar')
    <a href="{{ route('admin.dashboard') }}" class="nav-link active"><i class="fas fa-chart-line"></i> Dashboard</a>
    <a href="{{ route('admin.users') }}" class="nav-link"><i class="fas fa-users"></i> Kelola Users</a>
    <a href="{{ route('admin.flights') }}" class="nav-link"><i class="fas fa-plane"></i> Penerbangan</a>
    <a href="{{ route('admin.bookings') }}" class="nav-link"><i class="fas fa-book"></i> Kelola Booking</a>
    <a href="{{ route('admin.airlines') }}" class="nav-link"><i class="fas fa-building"></i> Maskapai</a>
    <a href="{{ route('admin.airports') }}" class="nav-link"><i class="fas fa-map-marker-alt"></i> Bandara</a>
    <a href="{{ route('admin.airplanes') }}" class="nav-link"><i class="fas fa-subway"></i> Pesawat</a>
@endsection

@section('content')
<div class="page-header">
    <h2><i class="fas fa-chart-line me-2"></i>Dashboard Administrator</h2>
    <p>Kelola operasional data penerbangan, airlines, bandara, dan transaksi user.</p>
</div>

<div class="row g-4 mt-2">
    <div class="col-md-3">
        <div class="card stat-card text-white bg-gradient-blue p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase mb-2 opacity-75">Penerbangan</h6>
                    <h2 class="fw-bold mb-0">{{ $totalFlights }}</h2>
                </div>
                <i class="fas fa-plane-departure fa-2x opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card text-white bg-gradient-emerald p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase mb-2 opacity-75">Total Booking</h6>
                    <h2 class="fw-bold mb-0">{{ $totalBookings }}</h2>
                </div>
                <i class="fas fa-receipt fa-2x opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card text-white bg-gradient-gold p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase mb-2 opacity-75">Pendapatan</h6>
                    <h4 class="fw-bold mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h4>
                </div>
                <i class="fas fa-wallet fa-2x opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card text-white bg-gradient-navy p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase mb-2 opacity-75">Customer</h6>
                    <h2 class="fw-bold mb-0">{{ $totalUsers }}</h2>
                </div>
                <i class="fas fa-users-cog fa-2x opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-3">
    <div class="col-md-4">
        <div class="card stat-card text-white bg-gradient-purple p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase mb-2 opacity-75">Maskapai</h6>
                    <h2 class="fw-bold mb-0">{{ $totalAirlines }}</h2>
                </div>
                <i class="fas fa-building fa-2x opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="d-flex justify-content-between align-items-center p-4">
                <div>
                    <h6 class="text-uppercase mb-2 opacity-75">Bandara</h6>
                    <h2 class="fw-bold mb-0">{{ $totalAirports }}</h2>
                </div>
                <i class="fas fa-map-marker-alt fa-2x opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card text-white bg-gradient-red p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase mb-2 opacity-75">Staff & Manager</h6>
                    <h2 class="fw-bold mb-0">{{ $totalStaff }}</h2>
                </div>
                <i class="fas fa-users-cog fa-2x opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<!-- Recent Bookings -->
<div class="card content-card mt-5">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center" style="border-radius: 15px 15px 0 0;">
        <h5 class="fw-bold mb-0" style="color: #0a192f;"><i class="fas fa-clock me-2 text-warning"></i>Booking Terbaru</h5>
        <a href="{{ route('admin.bookings') }}" class="btn btn-sm btn-primary-custom">Lihat Semua</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom mb-0">
                <thead>
                    <tr><th>Kode</th><th>Customer</th><th>Penerbangan</th><th>Harga</th><th>Status</th><th>Tanggal</th></tr>
                </thead>
                <tbody>
                    @forelse($recentBookings as $booking)
                    <tr>
                        <td><strong>{{ $booking->booking_code }}</strong></td>
                        <td>{{ $booking->user->name ?? '-' }}</td>
                        <td>{{ $booking->flight->flight_number ?? '-' }} ({{ $booking->flight->airline->name ?? '-' }})</td>
                        <td>Rp {{ number_format($booking->total_price ?? 0, 0, ',', '.') }}</td>
                        <td>
                            @php
                                $sColor = match($booking->status) {
                                    'confirmed' => 'success',
                                    'cancelled' => 'danger',
                                    'paid' => 'info',
                                    default => 'warning'
                                };
                            @endphp
                            <span class="badge bg-{{ $sColor }}">{{ ucfirst($booking->status) }}</span>
                        </td>
                        <td>{{ $booking->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada booking.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Fast Actions -->
<div class="card content-card mt-4 p-4">
    <h5 class="fw-bold mb-3" style="color: #0a192f;"><i class="fas fa-cogs me-2 text-warning"></i>Akses Cepat</h5>
    <div class="row g-3">
        <div class="col-md-3">
            <a href="{{ route('admin.flights.create') }}" class="btn btn-outline-primary w-100 p-3 text-start">
                <i class="fas fa-plane-arrival me-2"></i> Jadwal Baru
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.users.create') }}" class="btn btn-outline-warning w-100 p-3 text-start text-dark">
                <i class="fas fa-user-plus me-2"></i> Tambah User
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.airlines') }}" class="btn btn-outline-success w-100 p-3 text-start">
                <i class="fas fa-building me-2"></i> Kelola Maskapai
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.bookings') }}" class="btn btn-outline-dark w-100 p-3 text-start">
                <i class="fas fa-book me-2"></i> Lihat Booking
            </a>
        </div>
    </div>
</div>
@endsection
