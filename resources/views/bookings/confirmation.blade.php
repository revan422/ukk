<!DOCTYPE html>

<html lang="id">



<head>

    <meta charset="UTF-8">

    <title>Konfirmasi Pemesanan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>



<body class="bg-light">

    <nav class="navbar navbar-dark bg-primary">

        <div class="container">

            <a class="navbar-brand" href="#">Konfirmasi Pemesanan</a>

        </div>

    </nav>



    <div class="container mt-5">

        <div class="row justify-content-center">

            <div class="col-md-8">

                <div class="card shadow">

                    <div class="card-header bg-white">

                        <h4 class="mb-0">Konfirmasi Pemesanan</h4>

                    </div>

                    <div class="card-body">

                        <div class="alert alert-info">

                            <h6>Ringkasan Pemesanan:</h6>

                            <p class="mb-1"><strong>Maskapai:</strong> {{ $flight->airline->name }} -

                                {{ $flight->flight_number }}</p>

                            <p class="mb-1"><strong>Rute:</strong> {{ $flight->departureAirport->name }} →

                                {{ $flight->arrivalAirport->name }}</p>

                            <p class="mb-1"><strong>Tanggal:</strong> {{ $flight->departure_time->format('d M Y') }}

                            </p>

                            <p class="mb-1"><strong>Waktu:</strong> {{ $flight->departure_time->format('H:i') }} -

                                {{ $flight->arrival_time->format('H:i') }}</p>

                            <p class="mb-1"><strong>Kursi:</strong> {{ $seat->seat_number }}

                                ({{ ucfirst($seat->seat_class ?? $bookingData['seat_class'] ?? 'Economy') }})</p>

                            <p class="mb-1"><strong>Penumpang:</strong> {{ $bookingData['passenger']['full_name'] }}

                            </p>



                            <hr>

                            <h5 class="text-success mb-0">Total Pembayaran: Rp

                                {{ number_format($bookingData['price'], 0, ',', '.') }}</h5>

                        </div>



                        <form action="{{ route('bookings.processPayment') }}" method="POST">

                            @csrf

                            <div class="alert alert-warning">

                                <p class="mb-0">

                                    <strong>Pembayaran melalui Midtrans Snap.</strong>

                                    Setelah menekan tombol "Booking Sekarang", Anda akan diarahkan ke halaman detail booking

                                    untuk melanjutkan pembayaran dengan berbagai metode pembayaran yang tersedia.

                                </p>

                            </div>

                            <button type="submit" class="btn btn-primary w-100 btn-lg">

                                <i class="bi bi-check-circle"></i> Booking Sekarang

                            </button>

                        </form>

                        <div class="mt-3 text-center">

                            <a href="{{ route('bookings.selectSeat', $flight->id) }}" class="text-muted">Kembali pilih kursi</a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>



</html>
