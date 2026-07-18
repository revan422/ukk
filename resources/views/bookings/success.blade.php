<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pemesanan Berhasil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-success">
        <div class="container">
            <a class="navbar-brand" href="#">Pemesanan Berhasil!</a>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-body text-center p-5">
                        <div class="mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor" class="bi bi-check-circle-fill text-success" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                            </svg>
                        </div>
                        <h2 class="text-success mb-3">Pemesanan Berhasil!</h2>
                        <p class="lead">Terima kasih, pemesanan Anda telah dikonfirmasi.</p>

                        <div class="alert alert-info text-start mt-4">
                            <h5>Detail E-Ticket:</h5>
                            <p class="mb-1"><strong>Kode Booking:</strong> {{ $booking->booking_code }}</p>
                            <p class="mb-1"><strong>Penerbangan:</strong> {{ $booking->flight->airline->name }} - {{ $booking->flight->flight_number }}</p>
                            <p class="mb-1"><strong>Rute:</strong> {{ $booking->flight->departureAirport->name }} → {{ $booking->flight->arrivalAirport->name }}</p>
                            <p class="mb-1"><strong>Tanggal:</strong> {{ $booking->flight->departure_time->format('d M Y') }}</p>
                            <p class="mb-1"><strong>Waktu:</strong> {{ $booking->flight->departure_time->format('H:i') }} - {{ $booking->flight->arrival_time->format('H:i') }}</p>
                            <p class="mb-1"><strong>Kursi:</strong> {{ $booking->seat_number }}</p>
                            <p class="mb-1"><strong>Penumpang:</strong> {{ $booking->passenger->full_name }}</p>
                            
                            @if($booking->shipping_cost > 0)
                                <hr>
                                <h6>Layanan Pengiriman (RajaOngkir):</h6>
                                <p class="mb-1"><strong>Alamat:</strong> {{ $booking->shipping_address }}, {{ $booking->shipping_city }}, {{ $booking->shipping_province }}</p>
                                <p class="mb-1"><strong>Ongkos Kirim:</strong> Rp {{ number_format($booking->shipping_cost, 0, ',', '.') }}</p>
                                <hr>
                                <p class="mb-0"><strong>Total Bayar (Termasuk Ongkir):</strong> Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                            @else
                                <p class="mb-0"><strong>Total Bayar:</strong> Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                            @endif
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">Kembali ke Dashboard</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
