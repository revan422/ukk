@extends('layouts.staff')

@section('title', 'Keluhan Pelanggan')
@section('panel_title', 'Staff Panel')
@section('role_name', 'Staff Operasional')
@section('role_badge', 'Staff')

@section('sidebar')
    <a href="{{ route('staff.dashboard') }}" class="nav-link"><i class="fas fa-chart-bar"></i> Dashboard</a>
    <a href="{{ route('staff.bookings') }}" class="nav-link"><i class="fas fa-clipboard-list"></i> Kelola Booking</a>
    <a href="{{ route('staff.flights') }}" class="nav-link"><i class="fas fa-plane"></i> Monitoring Penerbangan</a>
    <a href="{{ route('staff.complaints') }}" class="nav-link active"><i class="fas fa-comment-dots"></i> Keluhan Pelanggan</a>
@endsection

@section('content')
<div class="page-header">
    <h2><i class="fas fa-comment-dots me-2"></i>Keluhan Pelanggan</h2>
    <p>Daftar booking yang dibatalkan dan perlu ditindaklanjuti</p>
</div>

<div class="card content-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom mb-0">
                <thead>
                    <tr>
                        <th>Kode Booking</th>
                        <th>Customer</th>
                        <th>Penerbangan</th>
                        <th>Penumpang</th>
                        <th>Total Bayar</th>
                        <th>Tgl Cancel</th>
                        <th>Status Refund</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($complaints as $c)
                    <tr>
                        <td><strong>{{ $c->booking_code }}</strong></td>
                        <td>{{ $c->user->name ?? '-' }}</td>
                        <td>{{ $c->flight->airline->name ?? '-' }} ({{ $c->flight->flight_number ?? '-' }})</td>
                        <td>{{ $c->passenger->full_name ?? $c->passenger->name ?? '-' }}</td>
                        <td>Rp {{ number_format($c->total_price, 0, ',', '.') }}</td>
                        <td>{{ $c->updated_at->format('d M Y H:i') }}</td>
                        <td>
                            @if($c->payment && $c->payment->payment_status == 'refunded')
                                <span class="badge bg-success">Sudah Refund</span>
                            @else
                                <span class="badge bg-warning text-dark">Menunggu Refund</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Tidak ada keluhan yang perlu ditindaklanjuti.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
