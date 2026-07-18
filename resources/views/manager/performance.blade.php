<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Analisis Performa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-success">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">📈 Manager Panel - Performa</span>
            <div class="d-flex align-items-center">
                <span class="text-white me-3">{{ Auth::user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST">@csrf<button class="btn btn-danger btn-sm">Logout</button></form>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 bg-white p-3" style="min-height: 100vh;">
                <div class="nav flex-column">
                    <a href="{{ route('manager.dashboard') }}" class="nav-link">📊 Dashboard</a>
                    <a href="{{ route('manager.reports') }}" class="nav-link">📄 Laporan</a>
                    <a href="{{ route('manager.performance') }}" class="nav-link active">🚀 Performa</a>
                </div>
            </div>
            <div class="col-md-10 p-4">
                <h3 class="mb-4">Analisis Performa Rute Penerbangan</h3>
                <p class="text-muted">Data diurutkan berdasarkan rute dengan jumlah pemesanan tertinggi.</p>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Penerbangan</th>
                                        <th>Maskapai</th>
                                        <th>Rute</th>
                                        <th>Waktu Keberangkatan</th>
                                        <th>Jumlah Booking</th>
                                        <th>Tingkat Populer</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($flights as $index => $flight)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><strong>{{ $flight->flight_number }}</strong></td>
                                            <td>{{ $flight->airline->name }}</td>
                                            <td>{{ $flight->departureAirport->code }} → {{ $flight->arrivalAirport->code }}</td>
                                            <td>{{ $flight->departure_time->format('d M Y H:i') }}</td>
                                            <td>
                                                <span class="badge bg-primary rounded-pill fs-6">{{ $flight->bookings_count }}</span>
                                            </td>
                                            <td style="width: 25%;">
                                                @php
                                                    $maxBookings = $flights->max('bookings_count') ?: 1;
                                                    $percentage = ($flight->bookings_count / $maxBookings) * 100;
                                                @endphp
                                                <div class="progress" style="height: 10px;">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percentage }}%"></div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">Belum ada data performa penerbangan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
