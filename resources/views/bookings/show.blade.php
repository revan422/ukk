<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Booking - {{ $booking->booking_code }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    {{-- Memuat Script Midtrans Snap secara Otomatis dari Config --}}
    @php
        $isProduction = config('services.midtrans.is_production', false);
        $snapScriptUrl = $isProduction 
            ? 'https://app.midtrans.com/snap/snap.js' 
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
        $clientKey = config('services.midtrans.client_key');
    @endphp
    <script src="{{ $snapScriptUrl }}" data-client-key="{{ $clientKey }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        .status-badge { font-size: 0.9rem; padding: 0.4rem 1rem; }
        .detail-card { border-radius: 12px; overflow: hidden; }
        .detail-card .card-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
        .detail-label { font-size: 0.85rem; color: #6c757d; text-transform: uppercase; letter-spacing: 0.5px; }
        .detail-value { font-size: 1rem; font-weight: 500; }
        .pay-button { padding: 12px 30px; font-size: 1.1rem; border-radius: 50px; transition: all 0.3s; }
        .pay-button:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3); }
        .loading-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(255, 255, 255, 0.9); z-index: 9999; justify-content: center; align-items: center; }
        .loading-overlay.active { display: flex; }
    </style>
</head>

<body class="bg-light">
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="text-center">
            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 text-muted">Memproses pembayaran...</p>
        </div>
    </div>

    <nav class="navbar navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="bi bi-airplane-engines"></i> Sistem Maskapai
            </a>
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </div>
    </nav>

    <div class="container mt-4 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Normalisasi Status ke UpperCase untuk Pengecekan -->
                @php
                    $currentStatus = strtoupper($booking->status ?? 'PENDING');
                @endphp

                <!-- Header Status -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0">
                        <i class="bi bi-ticket-perforated"></i> Detail Booking
                    </h4>
                    <span class="badge bg-{{ in_array($currentStatus, ['PAID', 'CONFIRMED', 'SUCCESS']) ? 'success' : (in_array($currentStatus, ['UNPAID', 'PENDING']) ? 'warning' : 'danger') }} status-badge">
                        {{ $currentStatus }}
                    </span>
                </div>

                <!-- Main Booking Detail Card -->
                <div class="card detail-card shadow mb-4">
                    <div class="card-header py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="bi bi-receipt"></i> Informasi Penerbangan</h5>
                            <span class="badge bg-light text-dark">
                                <i class="bi bi-qr-code"></i> {{ $booking->booking_code }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="detail-label">Maskapai</div>
                                <div class="detail-value">{{ $booking->flight?->airline?->name ?? '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-label">Nomor Penerbangan</div>
                                <div class="detail-value">{{ $booking->flight?->flight_number ?? '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-label">Kelas</div>
                                <div class="detail-value">{{ ucfirst($booking->flight?->seat_class ?? 'Economy') }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-label">Nomor Kursi</div>
                                <div class="detail-value">{{ $booking->passenger?->seat_number ?? $booking->seat_number ?? '-' }}</div>
                            </div>
                            <div class="col-12"><hr></div>
                            <div class="col-md-6">
                                <div class="detail-label">Rute</div>
                                <div class="detail-value">
                                    <i class="bi bi-geo-alt-fill text-success"></i>
                                    {{ $booking->flight?->departureAirport?->city ?? '-' }} ({{ $booking->flight?->departureAirport?->code ?? '-' }})
                                    <i class="bi bi-arrow-right mx-2"></i>
                                    <i class="bi bi-geo-alt-fill text-danger"></i>
                                    {{ $booking->flight?->arrivalAirport?->city ?? '-' }} ({{ $booking->flight?->arrivalAirport?->code ?? '-' }})
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-label">Tanggal & Waktu</div>
                                <div class="detail-value">
                                    <i class="bi bi-calendar-event"></i>
                                    {{ $booking->flight?->departure_time ? \Carbon\Carbon::parse($booking->flight->departure_time)->format('d M Y') : '-' }}<br>
                                    <i class="bi bi-clock"></i>
                                    {{ $booking->flight?->departure_time ? \Carbon\Carbon::parse($booking->flight->departure_time)->format('H:i') : '-' }} -
                                    {{ $booking->flight?->arrival_time ? \Carbon\Carbon::parse($booking->flight->arrival_time)->format('H:i') : '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Passenger Info -->
                <div class="card detail-card shadow mb-4">
                    <div class="card-header py-3">
                        <h5 class="mb-0"><i class="bi bi-person-badge"></i> Data Penumpang</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="detail-label">Nama Lengkap</div>
                                <div class="detail-value">{{ $booking->passenger?->full_name ?? '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-label">Tanggal Lahir</div>
                                <div class="detail-value">
                                    {{ $booking->passenger?->date_of_birth ? \Carbon\Carbon::parse($booking->passenger->date_of_birth)->format('d M Y') : '-' }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-label">No. KTP/Paspor</div>
                                <div class="detail-value">{{ $booking->passenger?->id_card_number ?? '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-label">Jenis Kelamin</div>
                                <div class="detail-value">{{ ucfirst($booking->passenger?->gender ?? '-') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Section -->
                <div class="card detail-card shadow mb-4">
                    <div class="card-header py-3">
                        <h5 class="mb-0"><i class="bi bi-credit-card"></i> Informasi Pembayaran</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="detail-label">Status Pembayaran</div>
                                <div>
                                    @php
                                        $paymentStatus = strtoupper(optional($booking->payment)->payment_status ?? 'PENDING');
                                        $statusColor = match($paymentStatus) {
                                            'SUCCESS', 'PAID', 'SETTLEMENT' => 'success',
                                            'PENDING' => 'warning',
                                            'FAILED', 'EXPIRED', 'DENIED' => 'danger',
                                            'CANCELLED' => 'secondary',
                                            default => 'warning'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $statusColor }}">{{ $paymentStatus }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-label">Total Harga</div>
                                <div class="detail-value text-success fw-bold">
                                    Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>

                        @if(!in_array($currentStatus, ['PAID', 'CONFIRMED', 'SUCCESS', 'CANCELLED', 'FAILED']))
                        <hr>
                        <div class="text-center mt-3">
                            <p class="text-muted mb-3">
                                <i class="bi bi-info-circle"></i> Klik tombol di bawah untuk melanjutkan pembayaran via Midtrans.
                            </p>
                            <button type="button" class="btn btn-primary btn-lg pay-button" id="payButton" onclick="payNow()">
                                <i class="bi bi-wallet2"></i> Bayar Sekarang
                            </button>
                        </div>
                        @elseif(in_array($currentStatus, ['PAID', 'CONFIRMED', 'SUCCESS']))
                        <div class="text-center mt-3">
                            <div class="alert alert-success mb-0">
                                <i class="bi bi-check-circle-fill"></i> Pembayaran telah berhasil. E-Tiket Anda aktif.
                            </div>
                            <a href="{{ route('bookings.success', $booking->id) }}" class="btn btn-success mt-3">
                                <i class="bi bi-ticket"></i> Lihat E-Tiket
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('bookings.history') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali ke Riwayat
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
                        <i class="bi bi-house"></i> Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function payNow() {
            const button = document.getElementById('payButton');
            const loadingOverlay = document.getElementById('loadingOverlay');

            button.disabled = true;
            button.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Memproses...';
            loadingOverlay.classList.add('active');

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('{{ route("payment.create") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    booking_id: {{ $booking->id }}
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw new Error(err.error || 'Gagal memproses pembayaran'); });
                }
                return response.json();
            })
            .then(data => {
                loadingOverlay.classList.remove('active');

                if (data.snap_token) {
                    snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            window.location.href = '{{ route("bookings.success", $booking->id) }}';
                        },
                        onPending: function(result) {
                            alert('Pembayaran sedang diproses.');
                            window.location.reload();
                        },
                        onError: function(result) {
                            alert('Pembayaran gagal!');
                            window.location.reload();
                        },
                        onClose: function() {
                            button.disabled = false;
                            button.innerHTML = '<i class="bi bi-wallet2"></i> Bayar Sekarang';
                        }
                    });
                } else {
                    button.disabled = false;
                    button.innerHTML = '<i class="bi bi-wallet2"></i> Bayar Sekarang';
                    alert('Gagal mendapatkan token pembayaran.');
                }
            })
            .catch(error => {
                loadingOverlay.classList.remove('active');
                button.disabled = false;
                button.innerHTML = '<i class="bi bi-wallet2"></i> Bayar Sekarang';
                alert('Error: ' + error.message);
            });
        }
    </script>
</body>
</html>
