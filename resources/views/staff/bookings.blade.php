@extends('layouts.staff')

@section('title', 'Kelola Booking')
@section('panel_title', 'Staff Panel')
@section('role_name', 'Staff Operasional')
@section('role_badge', 'Staff')

@section('sidebar')
    <a href="{{ route('staff.dashboard') }}" class="nav-link"><i class="fas fa-chart-bar"></i> Dashboard</a>
    <a href="{{ route('staff.bookings') }}" class="nav-link active"><i class="fas fa-clipboard-list"></i> Kelola Booking</a>
    <a href="{{ route('staff.flights') }}" class="nav-link"><i class="fas fa-plane"></i> Monitoring Penerbangan</a>
    <a href="{{ route('staff.complaints') }}" class="nav-link"><i class="fas fa-comment-dots"></i> Keluhan Pelanggan</a>
@endsection

@section('content')
<div class="page-header">
    <h2><i class="fas fa-clipboard-list me-2"></i>Kelola Booking</h2>
    <p>Validasi, reschedule, dan cancel booking tiket</p>
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
                        <th>Aksi</th>
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
                        <td>
                            @if($booking->status !== 'cancelled')
                            <div class="d-flex gap-1">
                                <!-- Reschedule -->
                                <button class="btn btn-sm btn-primary-custom" data-bs-toggle="modal" data-bs-target="#rescheduleModal{{ $booking->id }}">
                                    <i class="fas fa-exchange-alt"></i>
                                </button>
                                <!-- Cancel -->
                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal{{ $booking->id }}">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            @else
                            <span class="text-muted small">Dibatalkan</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data booking.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Reschedule Modals -->
@foreach($bookings as $booking)
@if($booking->status !== 'cancelled')
<div class="modal fade modal-custom" id="rescheduleModal{{ $booking->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('staff.reschedule', $booking->id) }}" method="POST">
                @csrf
                <div class="modal-header"><h5 class="modal-title">Reschedule Booking</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <p>Booking: <strong>{{ $booking->booking_code }}</strong></p>
                    <p>Penerbangan Saat Ini: <strong>{{ $booking->flight->airline->name }} ({{ $booking->flight->flight_number }})</strong></p>
                    <div class="mb-3">
                        <label class="form-label">Pilih Penerbangan Baru</label>
                        <select name="new_flight_id" class="form-select" required>
                            <option value="">Pilih Penerbangan</option>
                            @foreach($flights as $flight)
                                <option value="{{ $flight->id }}">{{ $flight->airline->name }} - {{ $flight->departureAirport->city }} → {{ $flight->arrivalAirport->city }} ({{ $flight->departure_time->format('d M H:i') }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-gold-custom">Reschedule</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade modal-custom" id="cancelModal{{ $booking->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('staff.cancel', $booking->id) }}" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white"><h5 class="modal-title">Cancel Booking</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <p>Yakin ingin membatalkan booking <strong>{{ $booking->booking_code }}</strong>?</p>
                    <div class="mb-3">
                        <label class="form-label">Alasan Pembatalan</label>
                        <textarea name="reason" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                    <button type="submit" class="btn btn-danger">Ya, Batalkan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endforeach
@endsection
