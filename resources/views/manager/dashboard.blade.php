@extends('layouts.manager')

@section('title', 'Dashboard Manager')
@section('panel_title', 'Manager Panel')

@section('sidebar')
    <a href="{{ route('manager.dashboard') }}" class="nav-link active"><i class="fas fa-chart-line"></i> Dashboard</a>
    <a href="{{ route('manager.reports') }}" class="nav-link"><i class="fas fa-file-alt"></i> Laporan</a>
    <a href="{{ route('manager.performance') }}" class="nav-link"><i class="fas fa-rocket"></i> Performa</a>
    <a href="{{ route('manager.revenue') }}" class="nav-link"><i class="fas fa-money-bill-wave"></i> Revenue</a>
    <a href="{{ route('manager.occupancy') }}" class="nav-link"><i class="fas fa-chair"></i> Occupancy</a>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h2><i class="fas fa-chart-line me-2"></i>Dashboard Manager</h2>
        <p>Analisis & Strategi untuk Pengambilan Keputusan</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('manager.export-pdf') }}" class="btn btn-pdf"><i class="fas fa-file-pdf me-1"></i>Export PDF</a>
        <a href="{{ route('manager.export-excel') }}" class="btn btn-excel"><i class="fas fa-file-excel me-1"></i>Export Excel</a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card stat-card text-white" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
            <div class="card-body p-4">
                <h6 class="text-uppercase mb-3 opacity-75">Total Pendapatan</h6>
                <h3 class="fw-bold mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card text-white" style="background: linear-gradient(135deg, #0a192f 0%, #1e3c72 100%);">
            <div class="card-body p-4">
                <h6 class="text-uppercase mb-3 opacity-75">Total Booking</h6>
                <h3 class="fw-bold mb-0">{{ $totalBookings }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card text-white" style="background: linear-gradient(135deg, #f4b400 0%, #d49a00 100%);">
            <div class="card-body p-4">
                <h6 class="text-uppercase mb-3 opacity-75">Rata-rata Harga</h6>
                <h3 class="fw-bold mb-0">Rp {{ number_format($avgTicketPrice, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card text-white" style="background: linear-gradient(135deg, #e52d27 0%, #b31217 100%);">
            <div class="card-body p-4">
                <h6 class="text-uppercase mb-3 opacity-75">Cancel Rate</h6>
                <h3 class="fw-bold mb-0">{{ number_format($cancellationRate, 1) }}%</h3>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card content-card">
            <div class="card-header bg-white py-3" style="border-radius: 15px 15px 0 0;">
                <h5 class="mb-0 fw-bold" style="color: #0a192f;">📊 Pendapatan per Maskapai</h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card content-card">
            <div class="card-header bg-white py-3" style="border-radius: 15px 15px 0 0;">
                <h5 class="mb-0 fw-bold" style="color: #0a192f;">📈 Tren Booking 6 Bulan</h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card content-card">
            <div class="card-header bg-white py-3" style="border-radius: 15px 15px 0 0;">
                <h5 class="mb-0 fw-bold" style="color: #0a192f;">Detail Pendapatan per Maskapai</h5>
            </div>
            <div class="card-body">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Maskapai</th><th class="text-center">Booking</th><th class="text-end">Pendapatan</th></tr>
                    </thead>
                    <tbody>
                    @foreach($revenueByAirline as $item)
                        <tr>
                            <td><strong>{{ $item->airline_name }}</strong></td>
                            <td class="text-center"><span class="badge bg-primary rounded-pill">{{ $item->total_bookings }}</span></td>
                            <td class="text-end text-success fw-bold">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card content-card">
            <div class="card-header bg-white py-3" style="border-radius: 15px 15px 0 0;">
                <h5 class="mb-0 fw-bold" style="color: #0a192f;">🏆 Top 5 Rute Terlaris</h5>
            </div>
            <div class="card-body">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>No</th><th>Rute</th><th class="text-end">Total Booking</th></tr>
                    </thead>
                    <tbody>
                    @foreach($topRoutes as $index => $route)
                        <tr>
                            <td><span class="badge bg-warning text-dark">#{{ $index + 1 }}</span></td>
                            <td><strong>{{ $route->from_code }} → {{ $route->to_code }}</strong></td>
                            <td class="text-end">{{ $route->total_bookings }} transaksi</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card content-card mt-4">
    <div class="card-header bg-white py-3" style="border-radius: 15px 15px 0 0;">
        <h5 class="mb-0 fw-bold" style="color: #0a192f;">🏅 Top Maskapai</h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            @foreach($topAirlines as $airline)
            <div class="col-md-4">
                <div class="p-3 bg-light rounded border">
                    <h6 class="fw-bold">{{ $airline->name }}</h6>
                    <small class="text-muted">{{ $airline->flights_count }} Penerbangan | {{ $airline->total_bookings ?? 0 }} Booking</small>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($revenueByAirline->pluck('airline_name')) !!},
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: {!! json_encode($revenueByAirline->pluck('total_revenue')) !!},
                backgroundColor: ['rgba(10,25,47,0.8)','rgba(17,153,142,0.8)','rgba(244,180,0,0.8)','rgba(229,45,39,0.8)','rgba(56,239,125,0.8)'],
                borderColor: ['rgba(10,25,47,1)','rgba(17,153,142,1)','rgba(244,180,0,1)','rgba(229,45,39,1)','rgba(56,239,125,1)'],
                borderWidth: 2, borderRadius: 8
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { callback: v => 'Rp ' + (v/1000000).toFixed(1) + 'M' } } }
        }
    });

    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyBookings->pluck('month')) !!},
            datasets: [
                { label: 'Jumlah Booking', data: {!! json_encode($monthlyBookings->pluck('total')) !!}, backgroundColor: 'rgba(17,153,142,0.2)', borderColor: 'rgba(17,153,142,1)', borderWidth: 3, tension: 0.4, fill: true, yAxisID: 'y' },
                { label: 'Pendapatan (Rp)', data: {!! json_encode($monthlyBookings->pluck('revenue')) !!}, backgroundColor: 'rgba(244,180,0,0.2)', borderColor: 'rgba(244,180,0,1)', borderWidth: 3, tension: 0.4, fill: true, yAxisID: 'y1' }
            ]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            scales: {
                y: { type: 'linear', display: true, position: 'left', beginAtZero: true, title: { display: true, text: 'Jumlah Booking' } },
                y1: { type: 'linear', display: true, position: 'right', beginAtZero: true, title: { display: true, text: 'Pendapatan (Rp)' }, grid: { drawOnChartArea: false } }
            }
        }
    });
</script>
@endpush
