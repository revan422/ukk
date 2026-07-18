<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tingkat Hunian - Manager Dashboard</title>
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
        .progress-bar-custom {
            height: 20px;
            border-radius: 10px;
        }
        .occupancy-high {
            background: #28a745;
        }
        .occupancy-medium {
            background: #ffc107;
        }
        .occupancy-low {
            background: #dc3545;
        }
        .badge-occupancy {
            font-size: 0.85rem;
            padding: 5px 10px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark navbar-custom mb-4">
        <div class="container">
            <span class="navbar-brand fw-bold">📊 Tingkat Hunian Penerbangan</span>
            <a href="{{ route('manager.dashboard') }}" class="btn btn-outline-light btn-sm">← Kembali ke Dashboard</a>
        </div>
    </nav>

    <div class="container">
        <!-- Ringkasan -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Tingkat Hunian per Maskapai</div>
                    <div class="card-body">
                        @if($airlineOccupancy->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Maskapai</th>
                                            <th>Total Booking</th>
                                            <th>Total Kursi</th>
                                            <th>Occupancy Rate</th>
                                            <th>Progress</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($airlineOccupancy as $item)
                                            <tr>
                                                <td class="fw-bold">{{ $item->name }}</td>
                                                <td>{{ $item->total_bookings }}</td>
                                                <td>{{ $item->total_seats }}</td>
                                                <td>
                                                    <span class="badge badge-occupancy {{ $item->occupancy >= 70 ? 'bg-success' : ($item->occupancy >= 40 ? 'bg-warning text-dark' : 'bg-danger') }}">
                                                        {{ $item->occupancy }}%
                                                    </span>
                                                </td>
                                                <td style="width: 200px;">
                                                    <div class="progress progress-bar-custom">
                                                        <div class="progress-bar {{ $item->occupancy >= 70 ? 'occupancy-high' : ($item->occupancy >= 40 ? 'occupancy-medium' : 'occupancy-low') }}"
                                                             role="progressbar"
                                                             style="width: {{ $item->occupancy }}%;"
                                                             aria-valuenow="{{ $item->occupancy }}"
                                                             aria-valuemin="0"
                                                             aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">Belum ada data hunian maskapai.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail per Penerbangan -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Tingkat Hunian per Penerbangan</div>
                    <div class="card-body">
                        @if($flights->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Kode</th>
                                            <th>Maskapai</th>
                                            <th>Rute</th>
                                            <th>Keberangkatan</th>
                                            <th>Kursi Tersedia</th>
                                            <th>Confirmed Booking</th>
                                            <th>Occupancy</th>
                                            <th>Progress</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($flights as $flight)
                                            <tr>
                                                <td class="fw-bold">{{ $flight->flight_number }}</td>
                                                <td>{{ $flight->airline->name ?? '-' }}</td>
                                                <td>{{ $flight->departureAirport->code ?? '-' }} → {{ $flight->arrivalAirport->code ?? '-' }}</td>
                                                <td>{{ $flight->departure_time ? \Carbon\Carbon::parse($flight->departure_time)->format('d/m/Y H:i') : '-' }}</td>
                                                <td>{{ $flight->available_seats }}</td>
                                                <td>{{ $flight->confirmed_bookings }}</td>
                                                <td>
                                                    <span class="badge badge-occupancy {{ $flight->occupancy_percentage >= 70 ? 'bg-success' : ($flight->occupancy_percentage >= 40 ? 'bg-warning text-dark' : 'bg-danger') }}">
                                                        {{ $flight->occupancy_percentage }}%
                                                    </span>
                                                </td>
                                                <td style="width: 200px;">
                                                    <div class="progress progress-bar-custom">
                                                        <div class="progress-bar {{ $flight->occupancy_percentage >= 70 ? 'occupancy-high' : ($flight->occupancy_percentage >= 40 ? 'occupancy-medium' : 'occupancy-low') }}"
                                                             role="progressbar"
                                                             style="width: {{ $flight->occupancy_percentage }}%;"
                                                             aria-valuenow="{{ $flight->occupancy_percentage }}"
                                                             aria-valuemin="0"
                                                             aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">Belum ada data penerbangan.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
