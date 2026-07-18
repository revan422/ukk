@extends('layouts.manager')

@section('title', 'Laporan Penjualan')
@section('panel_title', 'Manager Panel')

@section('sidebar')
    <a href="{{ route('manager.dashboard') }}" class="nav-link"><i class="fas fa-chart-line"></i> Dashboard</a>
    <a href="{{ route('manager.reports') }}" class="nav-link active"><i class="fas fa-file-alt"></i> Laporan</a>
    <a href="{{ route('manager.performance') }}" class="nav-link"><i class="fas fa-rocket"></i> Performa</a>
    <a href="{{ route('manager.revenue') }}" class="nav-link"><i class="fas fa-money-bill-wave"></i> Revenue</a>
    <a href="{{ route('manager.occupancy') }}" class="nav-link"><i class="fas fa-chair"></i> Occupancy</a>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h2><i class="fas fa-file-alt me-2"></i>Laporan Penjualan</h2>
        <p>Data booking terkonfirmasi</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('manager.export-pdf') }}" class="btn btn-pdf"><i class="fas fa-file-pdf me-1"></i>PDF</a>
        <a href="{{ route('manager.export-excel') }}" class="btn btn-excel"><i class="fas fa-file-excel me-1"></i>Excel</a>
    </div>
</div>

<div class="card content-card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-custom mb-0">
                <thead>
                    <tr><th>No</th><th>Kode Booking</th><th>Tanggal</th><th>Customer</th><th>Penerbangan</th><th class="text-end">Total Harga</th></tr>
                </thead>
                <tbody>
                    @forelse($bookings as $index => $booking)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $booking->booking_code }}</strong></td>
                        <td>{{ $booking->created_at->format('d M Y H:i') }}</td>
                        <td>{{ $booking->user->name ?? '-' }}</td>
                        <td>{{ $booking->flight->airline->name ?? '-' }} ({{ $booking->flight->flight_number ?? '-' }})</td>
                        <td class="text-end text-success fw-bold">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data laporan.</td></tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="table-dark">
                        <th colspan="5" class="text-end">Total Pendapatan:</th>
                        <th class="text-end text-success">Rp {{ number_format($bookings->sum('total_price'), 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
