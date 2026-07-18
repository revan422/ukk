<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Konfirmasi Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Konfirmasi Pembayaran</a>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">Konfirmasi & Pembayaran</h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <h6>Ringkasan Pemesanan:</h6>
                            <p class="mb-1"><strong>Penerbangan:</strong> {{ $flight->airline->name }} - {{ $flight->flight_number }}</p>
                            <p class="mb-1"><strong>Rute:</strong> {{ $flight->departureAirport->name }} → {{ $flight->arrivalAirport->name }}</p>
                            <p class="mb-1"><strong>Tanggal:</strong> {{ $flight->departure_time->format('d M Y') }}</p>
                            <p class="mb-1"><strong>Waktu:</strong> {{ $flight->departure_time->format('H:i') }} - {{ $flight->arrival_time->format('H:i') }}</p>
                            <p class="mb-1"><strong>Kursi:</strong> {{ $seat->seat_number }} ({{ ucfirst($seat->seat_class) }})</p>
                            <p class="mb-1"><strong>Penumpang:</strong> {{ $bookingData['passenger']['full_name'] }}</p>
                            
                            @if(isset($bookingData['shipping']['required']) && $bookingData['shipping']['required'])
                                <hr>
                                <h6>Layanan Pengiriman Tiket Fisik (RajaOngkir):</h6>
                                <p class="mb-1"><strong>Alamat:</strong> {{ $bookingData['shipping']['address'] }}, {{ $bookingData['shipping']['city_name'] }}, {{ $bookingData['shipping']['province_name'] }}</p>
                                <p class="mb-1"><strong>Biaya Kirim:</strong> Rp {{ number_format($bookingData['shipping']['cost'], 0, ',', '.') }}</p>
                                <hr>
                                <h5 class="text-success mb-0">Total Pembayaran: Rp {{ number_format($bookingData['price'] + $bookingData['shipping']['cost'], 0, ',', '.') }}</h5>
                            @else
                                <hr>
                                <h5 class="text-success mb-0">Total Pembayaran: Rp {{ number_format($bookingData['price'], 0, ',', '.') }}</h5>
                            @endif
                        </div>

                        <form action="{{ route('bookings.processPayment') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Metode Pembayaran</label>
                                <select name="payment_method" class="form-select" required>
                                    <option value="">Pilih Metode Pembayaran</option>
                                    <option value="bank_transfer">Transfer Bank</option>
                                    <option value="credit_card">Kartu Kredit</option>
                                    <option value="debit_card">Kartu Debit</option>
                                    <option value="e_wallet">E-Wallet (OVO, GoPay, Dana)</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success w-100 btn-lg">Bayar Sekarang</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
