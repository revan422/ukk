<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}"><i class="bi bi-airplane-engines"></i> Sistem Maskapai</a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-box-arrow-right"></i> Logout</button>
            </form>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0"><i class="bi bi-clock-history"></i> Riwayat Pemesanan Saya</h3>
            <a href="{{ route('flights.search') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-search"></i> Cari Penerbangan Baru
            </a>
        </div>

        @if($bookings->isEmpty())
            <div class="alert alert-warning">
                <i class="bi bi-info-circle"></i> Anda belum memiliki riwayat pemesanan. Silakan cari penerbangan untuk mulai memesan.
            </div>
        @else
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Kode Booking</th>
                                    <th>Penerbangan</th>
                                    <th>Rute</th>
                                    <th>Tanggal</th>
                                    <th>Penumpang</th>
                                    <th>Status Booking</th>
                                    <th>Status Bayar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bookings as $booking)
                                    @php
                                        $paymentStatus = optional($booking->payment)->payment_status ?? 'PENDING';
                                        $payStatusColor = match($paymentStatus) {
                                            'SUCCESS' => 'success',
                                            'PENDING' => 'warning',
                                            'FAILED', 'EXPIRED' => 'danger',
                                            'CANCELLED' => 'secondary',
                                            'REFUNDED' => 'info',
                                            default => 'warning'
                                        };
                                    @endphp
                                    <tr>
                                        <td><strong>{{ $booking->booking_code }}</strong></td>
                                        <td>{{ $booking->flight->airline->name ?? '-' }}<br><small>{{ $booking->flight->flight_number ?? '-' }}</small></td>
                                        <td>{{ $booking->flight->departureAirport->code ?? '-' }} → {{ $booking->flight->arrivalAirport->code ?? '-' }}</td>
                                        <td>{{ optional($booking->flight->departure_time)->format('d M Y') ?? '-' }}</td>
                                        <td>{{ $booking->passenger->full_name ?? '-' }}</td>
                                        <td>
                                            @php
                                                $statusColor = match($booking->status) {
                                                    'PAID' => 'success',
                                                    'UNPAID' => 'warning',
                                                    'PENDING' => 'info',
                                                    'CANCELLED' => 'secondary',
                                                    'FAILED' => 'danger',
                                                    'REFUNDED' => 'info',
                                                    'confirmed' => 'success',
                                                    'pending' => 'warning',
                                                    default => 'secondary'
                                                };
                                            @endphp
                                            <span class="badge bg-{{ $statusColor }}">{{ $booking->status }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $payStatusColor }}">{{ $paymentStatus }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
