<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil Pencarian - SkyLine Airlines</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f6f9;
            min-height: 100vh;
        }
        .navbar-custom {
            background: linear-gradient(135deg, #0a192f 0%, #11998e 100%);
        }
        .filter-info {
            background: linear-gradient(135deg, #e0f7fa 0%, #b2ebf2 100%);
            border-left: 4px solid #11998e;
            padding: 15px 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .date-group-header {
            background: linear-gradient(135deg, #0a192f 0%, #1a3a5c 100%);
            color: white;
            padding: 12px 20px;
            border-radius: 10px 10px 0 0;
            margin-top: 25px;
            font-weight: 600;
        }
        .flight-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 15px;
            overflow: hidden;
            border: 2px solid transparent;
            transition: all 0.3s;
        }
        .flight-card:hover {
            border-color: #f4b400;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }
        .class-badge {
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 13px;
            display: inline-block;
        }
        .badge-economy {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }
        .badge-business {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
        }
        .badge-first {
            background: linear-gradient(135deg, #ffc107, #ff9800);
            color: #000;
        }
        .price-tag {
            font-size: 24px;
            font-weight: 700;
            color: #f4b400;
        }
        .btn-select {
            background: linear-gradient(135deg, #f4b400 0%, #d49a00 100%);
            color: #0a192f;
            border: none;
            padding: 10px 30px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-select:hover {
            background: linear-gradient(135deg, #d49a00 0%, #b38600 100%);
            color: #fff;
            transform: translateY(-2px);
        }
        .time-display {
            font-size: 28px;
            font-weight: 700;
            color: #0a192f;
        }
        .airport-code {
            font-size: 16px;
            color: #666;
            font-weight: 600;
        }
        .seat-info {
            background: #f8f9fa;
            padding: 8px 15px;
            border-radius: 6px;
            font-size: 14px;
        }
        .generated-notice {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border-left: 4px solid #f4b400;
            padding: 15px 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .flight-route-line {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .route-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
        }
        .route-line {
            flex: 1;
            height: 2px;
            background: linear-gradient(90deg, #11998e, #f4b400);
            max-width: 60px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark navbar-custom">
        <div class="container px-4">
            <span class="navbar-brand fw-bold">✈️ SkyLine Airlines - Hasil Pencarian</span>
            <a href="{{ route('flights.index') }}" class="btn btn-outline-light btn-sm">
                ← Cari Lagi
            </a>
        </div>
    </nav>

    <div class="container mt-4 mb-5">
        <!-- Filter Info -->
        <div class="filter-info">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 class="mb-2 fw-bold">
                        {{ $departureAirport->name }} ({{ $departureAirport->code }})
                        → {{ $arrivalAirport->name }} ({{ $arrivalAirport->code }})
                    </h5>
                    <p class="mb-0">
                        📅 {{ \Carbon\Carbon::parse($request->date)->format('d F Y') }} |
                        🎫 {{ $classLabels[$request->class] ?? 'Semua Kelas' }}
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <span class="badge bg-primary fs-6">
                        {{ $flights->count() }} Penerbangan Tersedia
                    </span>
                </div>
            </div>
        </div>

        @if($flights->isEmpty())
            <div class="alert alert-warning mt-4">
                <h5 class="fw-bold">😕 Tidak Ada Penerbangan Tersedia</h5>
                <p class="mb-0">Maaf, tidak ada penerbangan {{ $classLabels[$request->class] }} untuk rute dan tanggal yang dipilih.</p>
                <hr>
                <p class="mb-0"><strong>Saran:</strong></p>
                <ul class="mb-0">
                    <li>Coba pilih tanggal lain</li>
                    <li>Coba pilih kelas yang berbeda</li>
                    <li>Periksa kembali bandara asal dan tujuan</li>
                </ul>
            </div>
        @else
            <!-- Group flights by date -->
            @php
                $groupedByDate = $flights->groupBy(function($flight) {
                    return $flight->departure_time->format('Y-m-d');
                });
            @endphp

            @foreach($groupedByDate as $date => $dateFlights)
                <div class="date-group-header">
                    📅 {{ \Carbon\Carbon::parse($date)->format('l, d F Y') }}
                    <span class="badge bg-light text-dark ms-2">{{ $dateFlights->count() }} penerbangan</span>
                </div>

                @foreach($dateFlights as $flight)
                    <div class="flight-card">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <!-- Airline & Flight Number -->
                                <div class="col-md-3">
                                    <h5 class="fw-bold mb-1" style="color: #0a192f;">
                                        {{ $flight->airline->name }}
                                    </h5>
                                    <p class="mb-0 text-muted">{{ $flight->flight_number }}</p>
                                    <span class="class-badge badge-{{ $flight->flight_class }}">
                                        {{ $classLabels[$flight->flight_class] }}
                                    </span>
                                </div>

                                <!-- Departure Time -->
                                <div class="col-md-3 text-center">
                                    <div class="time-display">
                                        {{ $flight->departure_time->format('H:i') }}
                                    </div>
                                    <div class="airport-code">{{ $flight->departureAirport->code }}</div>
                                    <small class="text-muted">{{ $flight->departureAirport->city }}</small>
                                </div>

                                <!-- Duration & Route -->
                                <div class="col-md-3 text-center">
                                    <div class="flight-route-line mb-2">
                                        <span class="route-dot" style="background: #11998e;"></span>
                                        <span class="route-line"></span>
                                        <span class="route-dot" style="background: #f4b400;"></span>
                                    </div>
                                    <small class="text-muted">
                                        ✈️ {{ $flight->departure_time->diffInMinutes($flight->arrival_time) }} menit
                                    </small>
                                    <div class="airport-code mt-1">
                                        {{ $flight->arrivalAirport->code }}
                                    </div>
                                    <small class="text-muted">{{ $flight->arrival_time->format('H:i') }}</small>
                                </div>

                                <!-- Price & Action -->
                                <div class="col-md-3 text-end">
                                    <div class="price-tag mb-2">
                                        Rp {{ number_format($flight->price, 0, ',', '.') }}
                                    </div>
                                    <div class="seat-info mb-3">
                                        🪑 {{ $flight->available_seats }} kursi tersedia
                                    </div>
                                    <a href="{{ route('bookings.selectSeat', $flight->id) }}" class="btn btn-select">
                                        Beli Tiket →
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforeach

            <!-- Info jika ini hasil generate otomatis -->
            @if($flights->count() > 0 && \Carbon\Carbon::parse($request->date)->diffInDays($flights->first()->departure_time) > 0)
                <div class="generated-notice">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="fw-bold mb-1">✨ Penerbangan Tersedia!</h5>
                            <p class="mb-0">
                                Penerbangan {{ $classLabels[$request->class] }} untuk rute
                                <strong>{{ $departureAirport->code }} → {{ $arrivalAirport->code }}</strong>
                                telah tersedia. Pilih tanggal yang sesuai dengan jadwal Anda.
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <span class="badge bg-success fs-6">Tersedia untuk 15 hari ke depan</span>
                        </div>
                    </div>
                </div>
            @endif
        @endif

        <!-- Back to Search -->
        <div class="text-center mt-4">
            <a href="{{ route('flights.index') }}" class="btn btn-outline-primary btn-lg">
                🔍 Cari Penerbangan Lain
            </a>
        </div>
    </div>
</body>
