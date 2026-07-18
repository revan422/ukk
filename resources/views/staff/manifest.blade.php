@extends('layouts.staff')

@section('title', 'Manifest Penumpang')
@section('panel_title', 'Staff Panel')
@section('role_name', 'Staff Operasional')
@section('role_badge', 'Staff')

@section('sidebar')
    <a href="{{ route('staff.dashboard') }}" class="nav-link"><i class="fas fa-chart-bar"></i> Dashboard</a>
    <a href="{{ route('staff.bookings') }}" class="nav-link"><i class="fas fa-clipboard-list"></i> Kelola Booking</a>
    <a href="{{ route('staff.flights') }}" class="nav-link active"><i class="fas fa-plane"></i> Monitoring Penerbangan</a>
    <a href="{{ route('staff.complaints') }}" class="nav-link"><i class="fas fa-comment-dots"></i> Keluhan Pelanggan</a>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h2><i class="fas fa-users me-2"></i>Manifest Penumpang</h2>
        <p>Penerbangan: <strong>{{ $flight->airline->name }} ({{ $flight->flight_number ?? 'FL' . str_pad($flight->id, 4, '0', STR_PAD_LEFT) }})</strong></p>
        <p class="text-muted">Rute: {{ $flight->departureAirport->city ?? '-' }} → {{ $flight->arrivalAirport->city ?? '-' }} | {{ $flight->departure_time ? $flight->departure_time->format('d M Y H:i') : '-' }}</p>
    </div>
    <a href="{{ route('staff.flights') }}" class="btn btn-outline-custom"><i class="fas fa-arrow-left me-1"></i>Kembali</a>
</div>

<div class="card content-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Penumpang</th>
                        <th>Jenis Kelamin</th>
                        <th>Kursi</th>
                        <th>Passport</th>
                        <th>Customer</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($passengers as $index => $passenger)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $passenger->full_name ?? $passenger->name }}</strong></td>
                        <td>{{ $passenger->gender ?? '-' }}</td>
                        <td><span class="badge bg-dark">{{ $passenger->seat_number ?? '-' }}</span></td>
                        <td>{{ $passenger->passport_number ?? '-' }}</td>
                        <td>{{ $passenger->bookings->first()->user->name ?? $passenger->user->name ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada penumpang untuk penerbangan ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
