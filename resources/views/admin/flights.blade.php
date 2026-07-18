@extends('layouts.admin')

@section('title', 'Kelola Penerbangan')
@section('panel_title', 'Admin Panel')
@section('role_name', 'Administrator')
@section('role_badge', 'Admin')

@section('sidebar')
    <a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="fas fa-chart-line"></i> Dashboard</a>
    <a href="{{ route('admin.users') }}" class="nav-link"><i class="fas fa-users"></i> Kelola Users</a>
    <a href="{{ route('admin.flights') }}" class="nav-link active"><i class="fas fa-plane"></i> Penerbangan</a>
    <a href="{{ route('admin.bookings') }}" class="nav-link"><i class="fas fa-book"></i> Kelola Booking</a>
    <a href="{{ route('admin.airlines') }}" class="nav-link"><i class="fas fa-building"></i> Maskapai</a>
    <a href="{{ route('admin.airports') }}" class="nav-link"><i class="fas fa-map-marker-alt"></i> Bandara</a>
    <a href="{{ route('admin.airplanes') }}" class="nav-link"><i class="fas fa-subway"></i> Pesawat</a>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h2><i class="fas fa-plane me-2"></i>Kelola Penerbangan</h2>
        <p>Manajemen jadwal dan rute penerbangan</p>
    </div>
    <a href="{{ route('admin.flights.create') }}" class="btn btn-gold-custom">
        <i class="fas fa-plus me-1"></i>Tambah Penerbangan
    </a>
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
                        <th>Harga</th>
                        <th>Kursi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($flights as $index => $flight)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $flight->flight_number ?? 'FL' . str_pad($flight->id, 4, '0', STR_PAD_LEFT) }}</strong></td>
                        <td>{{ $flight->airline->name ?? '-' }}</td>
                        <td>{{ $flight->departureAirport->city ?? '-' }} ({{ $flight->departureAirport->code ?? '-' }}) → {{ $flight->arrivalAirport->city ?? '-' }} ({{ $flight->arrivalAirport->code ?? '-' }})</td>
                        <td>{{ $flight->departure_time ? $flight->departure_time->format('d M Y H:i') : '-' }}</td>
                        <td>{{ $flight->arrival_time ? $flight->arrival_time->format('d M Y H:i') : '-' }}</td>
                        <td>Rp {{ number_format($flight->price ?? 0, 0, ',', '.') }}</td>
                        <td><span class="badge bg-info">{{ $flight->available_seats ?? 0 }}</span></td>
                        <td>
                            @php
                                $statusColor = match($flight->status) {
                                    'scheduled' => 'primary',
                                    'on_time' => 'success',
                                    'delayed' => 'warning',
                                    'cancelled' => 'danger',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $statusColor }}">{{ ucfirst(str_replace('_', ' ', $flight->status ?? 'scheduled')) }}</span>
                        </td>
                        <td>
                            <a href="{{ route('admin.flights.edit', $flight->id) }}" class="btn btn-sm btn-primary-custom">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.flights.delete', $flight->id) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus penerbangan ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
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
