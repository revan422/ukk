@extends('layouts.staff')

@section('title', 'Dashboard Staff')
@section('panel_title', 'Staff Panel')
@section('role_name', 'Staff Operasional')
@section('role_badge', 'Staff')

@section('sidebar')
    <a href="{{ route('staff.dashboard') }}" class="nav-link active"><i class="fas fa-chart-bar"></i> Dashboard</a>
    <a href="{{ route('staff.bookings') }}" class="nav-link"><i class="fas fa-clipboard-list"></i> Kelola Booking</a>
    <a href="{{ route('staff.flights') }}" class="nav-link"><i class="fas fa-plane"></i> Monitoring Penerbangan</a>
    <a href="{{ route('staff.complaints') }}" class="nav-link"><i class="fas fa-comment-dots"></i> Keluhan Pelanggan</a>
@endsection

@section('content')
<div class="page-header">
    <h2><i class="fas fa-chart-bar me-2"></i>Dashboard Staff Operasional</h2>
    <p>Kelola status pesanan tiket, monitoring penerbangan, dan layanan pelanggan</p>
</div>

<div class="row g-4 mt-2">
    <div class="col-md-4">
        <div class="card stat-card text-white bg-gradient-gold p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase mb-2 opacity-75">Booking Pending</h6>
                    <h2 class="fw-bold mb-0">{{ $pendingBookings }}</h2>
                </div>
                <i class="fas fa-clock fa-2x opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card text-white bg-gradient-emerald p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase mb-2 opacity-75">Booking Confirmed</h6>
                    <h2 class="fw-bold mb-0">{{ $confirmedBookings }}</h2>
                </div>
                <i class="fas fa-check-circle fa-2x opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card text-white bg-gradient-red p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase mb-2 opacity-75">Booking Cancelled</h6>
                    <h2 class="fw-bold mb-0">{{ $cancelledBookings }}</h2>
                </div>
                <i class="fas fa-times-circle fa-2x opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-3">
    <div class="col-md-4">
        <div class="card stat-card text-white bg-gradient-navy p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase mb-2 opacity-75">Booking Hari Ini</h6>
                    <h2 class="fw-bold mb-0">{{ $todayBookings }}</h2>
                </div>
                <i class="fas fa-calendar-day fa-2x opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card text-white bg-gradient-blue p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase mb-2 opacity-75">Penerbangan Hari Ini</h6>
                    <h2 class="fw-bold mb-0">{{ $totalFlights }}</h2>
                </div>
                <i class="fas fa-plane-departure fa-2x opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card text-white bg-gradient-purple p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase mb-2 opacity-75">Total Penumpang</h6>
                    <h2 class="fw-bold mb-0">{{ $totalPassengers }}</h2>
                </div>
                <i class="fas fa-users fa-2x opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<div class="card content-card mt-5 p-4">
    <h5 class="fw-bold mb-3" style="color: #0a192f;"><i class="fas fa-tasks me-2 text-info"></i>Alur Kerja Operasional</h5>
    <div class="row g-3">
        <div class="col-md-4">
            <div class="p-3 bg-light rounded border">
                <h6 class="fw-bold mb-2"><i class="fas fa-ticket-alt me-2 text-success"></i>Validasi Booking</h6>
                <p class="small text-muted mb-3">Tinjau pesanan masuk, konfirmasi pembayaran, dan layani reschedule.</p>
                <a href="{{ route('staff.bookings') }}" class="btn btn-sm btn-primary-custom">Kelola Booking</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 bg-light rounded border">
                <h6 class="fw-bold mb-2"><i class="fas fa-plane me-2 text-primary"></i>Monitoring Penerbangan</h6>
                <p class="small text-muted mb-3">Pantau jadwal penerbangan, status, dan manifest penumpang.</p>
                <a href="{{ route('staff.flights') }}" class="btn btn-sm btn-primary-custom">Lihat Penerbangan</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 bg-light rounded border">
                <h6 class="fw-bold mb-2"><i class="fas fa-comment-alt me-2 text-danger"></i>Penanganan Keluhan</h6>
                <p class="small text-muted mb-3">Respon keluhan, refund tiket, dan feedback penumpang.</p>
                <a href="{{ route('staff.complaints') }}" class="btn btn-sm btn-danger">Tinjau Keluhan</a>
            </div>
        </div>
    </div>
</div>
@endsection
