<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pemesanan - SkyLine</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-4">
                        <h3 class="fw-bold text-center mb-4">Konfirmasi Pemesanan</h3>

                        <!-- Detail Penerbangan -->
                        <div class="card mb-3 bg-light border-0">
                            <div class="card-body">
                                <h5 class="fw-bold text-primary">{{ $flight->airline->name ?? 'Maskapai' }}</h5>
                                <p class="mb-1"><strong>Rute:</strong> {{ $flight->departureAirport->name ?? '-' }} → {{ $flight->arrivalAirport->name ?? '-' }}</p>
                                <p class="mb-0"><strong>Nomor Kursi:</strong> <span class="badge bg-success fs-6">{{ $seat }}</span></p>
                            </div>
                        </div>

                        <!-- Detail Penumpang -->
                        <div class="card mb-4 border-0 bg-light">
                            <div class="card-body">
                                <h6 class="fw-bold">Data Penumpang:</h6>
                                <p class="mb-1"><strong>Nama:</strong> {{ $bookingData['passenger']['full_name'] ?? '-' }}</p>
                                <p class="mb-0"><strong>Jenis Kelamin:</strong> {{ ($bookingData['passenger']['gender'] ?? '') == 'male' ? 'Laki-laki' : 'Perempuan' }}</p>
                            </div>
                        </div>

                        <!-- Total Bayar -->
                        <div class="d-flex justify-content-between align-items-center mb-4 p-3 border rounded">
                            <span class="fs-5 fw-bold">Total Pembayaran:</span>
                            <span class="fs-4 fw-bold text-danger">Rp {{ number_format($bookingData['price'] ?? 0, 0, ',', '.') }}</span>
                        </div>

                        <!-- Form Submit -->
                        <form action="{{ route('bookings.processBooking') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-warning w-100 fw-bold py-2 fs-5 text-dark">
                                Buat Pesanan & Lanjut Bayar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
