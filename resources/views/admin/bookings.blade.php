@extends('layouts.admin')

@section('title', 'Kelola Booking')
@section('panel_title', 'Admin Panel')
@section('role_name', 'Administrator')
@section('role_badge', 'Admin')

@section('sidebar')
    <a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="fas fa-chart-line"></i> Dashboard</a>
    <a href="{{ route('admin.users') }}" class="nav-link"><i class="fas fa-users"></i> Kelola Users</a>
    <a href="{{ route('admin.flights') }}" class="nav-link"><i class="fas fa-plane"></i> Penerbangan</a>
    <a href="{{ route('admin.bookings') }}" class="nav-link active"><i class="fas fa-book"></i> Kelola Booking</a>
    <a href="{{ route('admin.airlines') }}" class="nav-link"><i class="fas fa-building"></i> Maskapai</a>
    <a href="{{ route('admin.airports') }}" class="nav-link"><i class="fas fa-map-marker-alt"></i> Bandara</a>
    <a href="{{ route('admin.airplanes') }}" class="nav-link"><i class="fas fa-subway"></i> Pesawat</a>
@endsection

@section('content')
<div class="page-header">
    <h2><i class="fas fa-book me-2"></i>Kelola Booking</h2>
    <p>Manajemen seluruh transaksi pemesanan tiket</p>
</div>

<div class="card content-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom mb-0">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Customer</th>
                        <th>Penerbangan</th>
                        <th>Penumpang</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                    <tr>
                        <td><strong>{{ $booking->booking_code }}</strong></td>
                        <td>{{ $booking->user->name ?? '-' }}</td>
                        <td>{{ $booking->flight->airline->name ?? '-' }} ({{ $booking->flight->flight_number ?? '-' }})</td>
                        <td>{{ $booking->passenger->full_name ?? $booking->passenger->name ?? '-' }}</td>
                        <td>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
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
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data booking.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
