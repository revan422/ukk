<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">Sistem Maskapai</a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm">Logout</button>
            </form>
        </div>
    </nav>

    <div class="container mt-5">
        <h3 class="mb-4">Riwayat Pemesanan Saya</h3>

        @if($bookings->isEmpty())
            <div class="alert alert-warning">
                Anda belum memiliki riwayat pemesanan. Silakan cari penerbangan untuk mulai memesan.
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
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bookings as $booking)
                                    <tr>
                                        <td><strong>{{ $booking->booking_code }}</strong></td>
                                        <td>{{ $booking->flight->airline->name }}<br><small>{{ $booking->flight->flight_number }}</small></td>
                                        <td>{{ $booking->flight->departureAirport->code }} → {{ $booking->flight->arrivalAirport->code }}</td>
                                        <td>{{ $booking->flight->departure_time->format('d M Y') }}</td>
                                        <td>{{ $booking->passenger->full_name }}</td>
                                        <td>
                                            @if($booking->status == 'confirmed')
                                                <span class="badge bg-success">Confirmed</span>
                                            @elseif($booking->status == 'pending')
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($booking->status) }}</span>
                                            @endif
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
</body>
</html>
