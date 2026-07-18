<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analisis Pendapatan - Manager Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f6f9;
        }
        .navbar-custom {
            background: linear-gradient(135deg, #0a192f 0%, #11998e 100%);
        }
        .card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        .card-header {
            background: linear-gradient(135deg, #0a192f 0%, #11998e 100%);
            color: white;
            border-radius: 12px 12px 0 0 !important;
            font-weight: 600;
        }
        .revenue-positive {
            color: #28a745;
        }
        .revenue-negative {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark navbar-custom mb-4">
        <div class="container">
            <span class="navbar-brand fw-bold">💰 Analisis Pendapatan</span>
            <a href="{{ route('manager.dashboard') }}" class="btn btn-outline-light btn-sm">← Kembali ke Dashboard</a>
        </div>
    </nav>

    <div class="container">
        <!-- Harian -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">📅 Pendapatan Harian (30 Hari Terakhir)</div>
                    <div class="card-body">
                        @if($dailyRevenue->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Jumlah Booking</th>
                                            <th>Total Pendapatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dailyRevenue as $item)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                                                <td>{{ $item->bookings }}</td>
                                                <td class="fw-bold revenue-positive">Rp {{ number_format($item->revenue, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">Belum ada data pendapatan harian.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Mingguan -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">📆 Pendapatan Mingguan (3 Bulan Terakhir)</div>
                    <div class="card-body">
                        @if($weeklyRevenue->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Minggu</th>
                                            <th>Jumlah Booking</th>
                                            <th>Total Pendapatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($weeklyRevenue as $item)
                                            <tr>
                                                <td>{{ $item->week }}</td>
                                                <td>{{ $item->bookings }}</td>
                                                <td class="fw-bold revenue-positive">Rp {{ number_format($item->revenue, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">Belum ada data pendapatan mingguan.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulanan -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">📊 Pendapatan Bulanan (1 Tahun Terakhir)</div>
                    <div class="card-body">
                        @if($monthlyRevenue->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Bulan</th>
                                            <th>Jumlah Booking</th>
                                            <th>Total Pendapatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($monthlyRevenue as $item)
                                            <tr>
                                                <td>{{ $item->month }}</td>
                                                <td>{{ $item->bookings }}</td>
                                                <td class="fw-bold revenue-positive">Rp {{ number_format($item->revenue, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">Belum ada data pendapatan bulanan.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
