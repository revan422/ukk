<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pemesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Konfirmasi Pemesanan</a>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-primary text-white py-3">
                        <h4 class="card-title mb-0 fw-bold">Konfirmasi Pemesanan</h4>
                    </div>
                    <div class="card-body p-4">
                        
                        {{-- Ringkasan Pemesanan --}}
                        <div class="alert alert-info border-0 shadow-sm p-3 mb-4">
                            <h6 class="fw-bold mb-3 text-dark">Ringkasan Pemesanan:</h6>
                            <p class="mb-1 text-dark">
                                <strong>Maskapai:</strong> {{ $flight->airline->name ?? 'Maskapai' }} - {{ $flight->flight_number }}
                            </p>
                            <p class="mb-1 text-dark">
                                <strong>Rute:</strong> {{ $flight->departureAirport->name ?? '' }} → {{ $flight->arrivalAirport->name ?? '' }}
                            </p>
                            <p class="mb-1 text-dark">
                                <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($flight->departure_time)->format('d M Y') }}
                            </p>
                            <p class="mb-1 text-dark">
                                <strong>Waktu:</strong> {{ \Carbon\Carbon::parse($flight->departure_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($flight->arrival_time)->format('H:i') }}
                            </p>
                            <p class="mb-1 text-dark">
                                <strong>Kursi:</strong> {{ $seat->seat_number }} ({{ ucfirst($seat->seat_class ?? $bookingData['seat_class'] ?? 'Economy') }})
                            </p>
                            <p class="mb-1 text-dark">
                                <strong>Penumpang:</strong> {{ $bookingData['passenger']['full_name'] ?? '-' }}
                            </p>

                            <hr>
                            <h5 class="text-success fw-bold mb-0">
                                Total Pembayaran: Rp {{ number_format($bookingData['price'], 0, ',', '.') }}
                            </h5>
                        </div>

                        {{-- Form Pembayaran & Indikator Metode Midtrans --}}
                        <form action="{{ route('bookings.processPayment') }}" method="POST">
                            @csrf
                            <div class="alert alert-warning border-0 shadow-sm mb-4">
                                <p class="mb-2 fw-bold text-dark">Metode Pembayaran (Midtrans Payment Gateway):</p>
                                <div class="d-flex flex-wrap gap-2 mb-2">
                                    <span class="badge bg-secondary p-2">Virtual Account (BCA, Mandiri, BRI, BNI)</span>
                                    <span class="badge bg-success p-2">QRIS / GoPay / ShopeePay</span>
                                    <span class="badge bg-dark p-2">Kartu Kredit / Debit</span>
                                </div>
                                <small class="text-muted d-block mt-2">
                                    Setelah menekan tombol "Booking Sekarang", Anda akan diarahkan ke halaman detail booking untuk memilih metode pembayaran di atas.
                                </small>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 btn-lg fw-bold py-3 shadow-sm">
                                <i class="bi bi-check-circle"></i> Booking Sekarang
                            </button>
                        </form>

                        <div class="mt-3 text-center">
                            <a href="{{ route('bookings.selectSeat', $flight->id) }}" class="text-muted text-decoration-none">
                                &larr; Kembali pilih kursi
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
