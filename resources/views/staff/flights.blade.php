@extends('layouts.staff')

@section('title', 'Monitoring Penerbangan')
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
<div class="page-header">
    <h2><i class="fas fa-plane me-2"></i>Monitoring Penerbangan</h2>
    <p>Pantau jadwal, status, dan manifest penumpang per penerbangan</p>
</div>

<div class="card content-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Maskapai</th>
                        <th>Rute</th>
                        <th>Keberangkatan</th>
                        <th>Kedatangan</th>
                        <th>Pesawat</th>
                        <th>Booking</th>
                        <th>Status</th>
                        <th>Manifest</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($flights as $index => $flight)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $flight->flight_number ?? 'FL' . str_pad($flight->id, 4, '0', STR_PAD_LEFT) }}</strong></td>
                        <td>{{ $flight->airline->name ?? '-' }}</td>
                        <td>{{ $flight->departureAirport->city ?? '-' }} → {{ $flight->arrivalAirport->city ?? '-' }}</td>
                        <td>{{ $flight->departure_time ? $flight->departure_time->format('d M Y H:i') : '-' }}</td>
                        <td>{{ $flight->arrival_time ? $flight->arrival_time->format('d M Y H:i') : '-' }}</td>
                        <td>{{ $flight->airplane->model ?? '-' }}</td>
                        <td><span class="badge bg-info">{{ $flight->confirmed_bookings ?? 0 }}</span></td>
                        <td>
                            @php
                                $sColor = match($flight->status) {
                                    'scheduled' => 'primary',
                                    'on_time' => 'success',
                                    'delayed' => 'warning',
                                    'cancelled' => 'danger',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $sColor }}">{{ ucfirst(str_replace('_', ' ', $flight->status ?? 'scheduled')) }}</span>
                        </td>
                        <td>
                            <a href="{{ route('staff.manifest', $flight->id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-users"></i> Manifest
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="10" class="text-center text-muted py-4">Belum ada data penerbangan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
