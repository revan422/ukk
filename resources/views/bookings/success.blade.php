<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Pemesanan Berhasil - {{ $booking->booking_code }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .e-ticket-card {
            border-radius: 16px;
            overflow: hidden;
            border: none;
        }
        .e-ticket-card .card-header {
            background: linear-gradient(135deg, #1a73e8 0%, #0d47a1 100%);
            color: white;
            padding: 20px;
        }
        .e-ticket-header {
            border-bottom: 2px dashed #dee2e6;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }
        .success-icon {
            font-size: 4rem;
            color: #4caf50;
        }
        @media print {
            .no-print { display: none !important; }
            .card { box-shadow: none !important; border: 2px solid #000 !important; }
        }
    </style>
</head>

<body class="bg-light">
    <nav class="navbar navbar-dark bg-success no-print">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="bi bi-check-circle"></i> Pembayaran Berhasil!</a>
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
            </form>
        </div>
    </nav>

    <div class="container mt-4 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Success Message -->
                <div class="text-center mb-4 no-print">
                    <div class="success-icon">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <h2 class="text-success mt-2">Pembayaran Berhasil!</h2>
                    <p class="text-muted">E-Tiket Anda telah aktif. Silakan simpan tiket ini untuk boarding.</p>
                </div>

                <!-- E-Ticket Card -->
                <div class="card e-ticket-card shadow">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0"><i class="bi bi-ticket-perforated"></i> E-Tiket</h4>
                                <small class="text-white-50">Boarding Pass</small>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold" style="font-size: 1.2rem;">{{ $booking->booking_code }}</div>
                                <small class="text-white-50">Kode Booking</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <!-- Flight Info -->
                        <div class="e-ticket-header">
                            <div class="row align-items-center">
                                <div class="col-md-4 text-center mb-3 mb-md-0">
                                    <div class="text-muted small">Keberangkatan</div>
                                    <div class="fw-bold" style="font-size: 1.8rem;">
                                        {{ $booking->flight->departureAirport->code ?? '-' }}
                                    </div>
                                    <div>{{ $booking->flight->departureAirport->city ?? '-' }}</div>
                                    <div class="text-muted small">
                                        {{ $booking->flight->departure_time->format('H:i') }}
                                    </div>
                                    <div class="text-muted small">
                                        {{ $booking->flight->departure_time->format('d M Y') }}
                                    </div>
                                </div>
                                <div class="col-md-4 text-center mb-3 mb-md-0">
                                    <div class="text-muted small">{{ $booking->flight->flight_number ?? '-' }}</div>
                                    <div class="text-muted">
                                        <i class="bi bi-airplane-fill"></i>
                                    </div>
                                    <div class="text-muted small">Durasi</div>
                                </div>
                                <div class="col-md-4 text-center">
                                    <div class="text-muted small">Kedatangan</div>
                                    <div class="fw-bold" style="font-size: 1.8rem;">
                                        {{ $booking->flight->arrivalAirport->code ?? '-' }}
                                    </div>
                                    <div>{{ $booking->flight->arrivalAirport->city ?? '-' }}</div>
                                    <div class="text-muted small">
                                        {{ $booking->flight->arrival_time->format('H:i') }}
                                    </div>
                                    <div class="text-muted small">
                                        {{ $booking->flight->arrival_time->format('d M Y') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Detail Rows -->
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="text-muted small">Maskapai</div>
                                <div class="fw-bold">{{ $booking->flight->airline->name ?? '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small">Penumpang</div>
                                <div class="fw-bold">{{ $booking->passenger->full_name ?? '-' }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-muted small">Kursi</div>
                                <div class="fw-bold">{{ $booking->seat_number ?? '-' }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-muted small">Kelas</div>
                                <div class="fw-bold">{{ ucfirst($booking->flight->seat_class ?? 'Economy') }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-muted small">Status</div>
                                <span class="badge bg-success">PAID</span>
                            </div>
                        </div>

                        <hr>

                        <!-- Payment Info -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="text-muted small">Total Pembayaran</div>
                                <div class="fw-bold text-success" style="font-size: 1.3rem;">
                                    Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <div class="text-muted small">Status Pembayaran</div>
                                <span class="badge bg-success">LUNAS</span>
                                @if(optional($booking->payment)->payment_type)
                                <div class="text-muted small mt-1">
                                    {{ $booking->payment->payment_type }}
                                </div>
                                @endif
                            </div>
                        </div>

                        @if(optional($booking->payment)->paid_at)
                        <div class="mt-2">
                            <div class="text-muted small">Dibayar pada</div>
                            <div>{{ $booking->payment->paid_at->format('d M Y H:i:s') }}</div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-between mt-4 no-print">
                    <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-outline-primary">
                        <i class="bi bi-info-circle"></i> Detail Booking
                    </a>
                    <div>
                        <button onclick="window.print()" class="btn btn-secondary me-2">
                            <i class="bi bi-printer"></i> Cetak
                        </button>
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">
                            <i class="bi bi-house"></i> Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
