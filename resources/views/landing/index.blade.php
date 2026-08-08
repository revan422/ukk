<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkyLine Airlines - Pesan Tiket Pesawat Murah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="manifest" href="/manifest.webmanifest">
    <meta name="theme-color" content="#0a192f">
    <style>
        :root {
            --primary-navy: #0a192f;
            --secondary-navy: #112240;
            --gold: #f4b400;
            --gold-dark: #d49a00;
            --light-blue: #e6f1ff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
            overflow-x: hidden;
        }

        /* Navbar */
        .navbar-custom {
            background: linear-gradient(135deg, var(--primary-navy) 0%, var(--secondary-navy) 100%);
            padding: 15px 0;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-size: 28px;
            font-weight: 800;
            color: white !important;
        }

        .navbar-brand span {
            color: var(--gold);
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            margin: 0 10px;
            transition: all 0.3s;
        }

        .nav-link:hover {
            color: var(--gold) !important;
            transform: translateY(-2px);
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--primary-navy) 0%, var(--secondary-navy) 50%, #1a3a5c 100%);
            min-height: 600px;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23f4b400" fill-opacity="0.05" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
            background-size: cover;
            background-position: bottom;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            padding: 80px 0 60px;
        }

        .hero-title {
            color: white;
            font-size: 48px;
            font-weight: 800;
            margin-bottom: 20px;
            text-align: center;
        }

        .hero-title span {
            color: var(--gold);
        }

        .hero-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 18px;
            text-align: center;
            margin-bottom: 40px;
        }

        /* Search Box */
        .search-box {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            margin-bottom: 40px;
        }

        .search-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
            border-bottom: 2px solid #e0e0e0;
        }

        .search-tab {
            padding: 12px 30px;
            background: none;
            border: none;
            font-weight: 600;
            color: #666;
            cursor: pointer;
            position: relative;
            transition: all 0.3s;
        }

        .search-tab.active {
            color: var(--primary-navy);
        }

        .search-tab.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--gold);
        }

        .search-tab i {
            margin-right: 8px;
        }

        .form-control,
        .form-select {
            border-radius: 10px;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            transition: all 0.3s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 0.2rem rgba(244, 180, 0, 0.25);
        }

        .form-label {
            font-weight: 600;
            color: var(--primary-navy);
            margin-bottom: 8px;
        }

        .btn-search {
            background: linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);
            color: var(--primary-navy);
            border: none;
            padding: 14px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 16px;
            width: 100%;
            transition: all 0.3s;
        }

        .btn-search:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(244, 180, 0, 0.4);
            color: white;
        }

        /* Features Section */
        .features-section {
            padding: 60px 0;
            background: white;
        }

        .feature-card {
            text-align: center;
            padding: 30px 20px;
            border-radius: 15px;
            transition: all 0.3s;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 32px;
        }

        .feature-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--primary-navy);
            margin-bottom: 10px;
        }

        .feature-desc {
            color: #666;
            font-size: 14px;
        }

        /* Popular Destinations */
        .destinations-section {
            padding: 80px 0;
            background: var(--light-blue);
        }

        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-title h2 {
            font-size: 36px;
            font-weight: 800;
            color: var(--primary-navy);
            margin-bottom: 10px;
        }

        .section-title p {
            color: #666;
            font-size: 16px;
        }

        .destination-card {
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            height: 300px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
        }

        .destination-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }

        .destination-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .destination-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(10, 25, 47, 0.9), transparent);
            padding: 30px 20px 20px;
            color: white;
        }

        .destination-name {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .destination-flights {
            font-size: 14px;
            opacity: 0.9;
        }

        /* Cheap Flights */
        .flights-section {
            padding: 80px 0;
            background: white;
        }

        .flight-card {
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            transition: all 0.3s;
        }

        .flight-card:hover {
            border-color: var(--gold);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transform: translateY(-5px);
        }

        .airline-logo {
            width: 60px;
            height: 60px;
            background: var(--light-blue);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: var(--primary-navy);
        }

        .flight-route {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-navy);
        }

        .flight-time {
            color: #666;
            font-size: 14px;
        }

        .flight-price {
            font-size: 28px;
            font-weight: 800;
            color: var(--gold);
        }

        /* Airlines Section */
        .airlines-section {
            padding: 60px 0;
            background: var(--light-blue);
        }

        .airline-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
        }

        .airline-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .airline-code {
            font-size: 32px;
            font-weight: 800;
            color: var(--gold);
            margin-bottom: 10px;
        }

        /* Footer */
        .footer {
            background: var(--primary-navy);
            color: white;
            padding: 60px 0 30px;
        }

        .footer-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
            color: var(--gold);
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 10px;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s;
        }

        .footer-links a:hover {
            color: var(--gold);
            padding-left: 5px;
        }

        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .social-links a {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            transition: all 0.3s;
        }

        .social-links a:hover {
            background: var(--gold);
            transform: translateY(-3px);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 40px;
            padding-top: 20px;
            text-align: center;
            color: rgba(255, 255, 255, 0.6);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 32px;
            }

            .search-tabs {
                flex-direction: column;
            }

            .flight-route {
                font-size: 18px;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('landing') }}">
                <i class="fas fa-plane-departure me-2"></i>
                Sky<span>Line</span> Airlines
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('landing') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#flights">Penerbangan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#destinations">Destinasi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#airlines">Maskapai</a>
                    </li>
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('bookings.history') }}">Riwayat Booking</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="dropdown-item" type="submit">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-outline-light ms-2" href="{{ route('register') }}">Daftar</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <div class="container">
                <h1 class="hero-title">
                    Pesan Tiket Pesawat <span>Murah</span>
                </h1>
                <p class="hero-subtitle">
                    Temukan penerbangan terbaik ke berbagai destinasi dengan harga terjangkau
                </p>

                <!-- Search Box -->
                <div class="search-box">
                    <div class="search-tabs">
                        <button class="search-tab active" onclick="switchTab('flight')">
                            <i class="fas fa-plane"></i> Penerbangan
                        </button>
                    </div>

                    <!-- Flight Search Form -->
                    <form id="flightSearchForm" action="{{ route('flights.index') }}" method="GET">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Dari</label>
                                <select name="departure" class="form-select" required>
                                    <option value="">Pilih Bandara</option>
                                    @foreach (\App\Models\Airport::all() as $airport)
                                        <option value="{{ $airport->id }}">{{ $airport->name }} ({{ $airport->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Ke</label>
                                <select name="arrival" class="form-select" required>
                                    <option value="">Pilih Bandara</option>
                                    @foreach (\App\Models\Airport::all() as $airport)
                                        <option value="{{ $airport->id }}">{{ $airport->name }}
                                            ({{ $airport->code }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Tanggal</label>
                                <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}"
                                    required min="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Kelas</label>
                                <select name="class" class="form-select" required>
                                    <option value="economy">Economy</option>
                                    <option value="business">Business</option>
                                    <option value="first">First Class</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-search">
                                    <i class="fas fa-search me-2"></i>Cari
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-tags"></i>
                        </div>
                        <h3 class="feature-title">Harga Terbaik</h3>
                        <p class="feature-desc">Dapatkan harga tiket pesawat termurah dengan jaminan harga terbaik</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3 class="feature-title">Pembayaran Aman</h3>
                        <p class="feature-desc">Transaksi aman dengan sistem pembayaran terenkripsi</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h3 class="feature-title">Layanan 24/7</h3>
                        <p class="feature-desc">Customer service siap membantu Anda kapan saja</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-plane"></i>
                        </div>
                        <h3 class="feature-title">Ribuan Penerbangan</h3>
                        <p class="feature-desc">Pilihan penerbangan ke berbagai destinasi domestik & internasional</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Destinations -->
    @php
        $destinationImages = [
            'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=600&auto=format&fit=crop&q=80', // Jakarta (Airplane wing in clouds)
            'https://images.unsplash.com/photo-1540962351504-03099e0a754b?w=600&auto=format&fit=crop&q=80', // Surabaya (Airplane in blue sky)
            'https://images.unsplash.com/photo-1556388158-158ea5ccacbd?w=600&auto=format&fit=crop&q=80', // Bali (Airplane on runway/tarmac)
            'https://images.unsplash.com/photo-1506012787146-f92b2d7d6d96?w=600&auto=format&fit=crop&q=80', // Medan (Airplane wing sunset)
            'https://images.unsplash.com/photo-1525624286412-4099c83c1bc8?w=600&auto=format&fit=crop&q=80', // Makassar (Airplane flying)
            'https://images.unsplash.com/photo-1569154941061-e231b4725ef1?w=600&auto=format&fit=crop&q=80', // Batam (Airplane passenger jet)
        ];
    @endphp
    <section class="destinations-section" id="destinations">
        <div class="container">
            <div class="section-title">
                <h2>Destinasi Populer</h2>
                <p>Jelajahi destinasi favorit para traveler</p>
            </div>
            <div class="row">
                @foreach ($popularDestinations as $airport)
                    <div class="col-md-4 col-sm-6">
                        <div class="destination-card">
                            <img src="{{ $destinationImages[$loop->index % count($destinationImages)] }}"
                                alt="{{ $airport->name }}" class="destination-img">
                            <div class="destination-overlay">
                                <h3 class="destination-name">{{ $airport->city }}</h3>
                                <p class="destination-flights">{{ $airport->departure_flights_count }} penerbangan</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Cheap Flights -->
    <section class="flights-section" id="flights">
        <div class="container">
            <div class="section-title">
                <h2>Penerbangan Murah</h2>
                <p>Promo spesial untuk perjalanan Anda</p>
            </div>
            <div class="row">
                @foreach ($cheapFlights as $flight)
                    <div class="col-md-6">
                        <div class="flight-card">
                            <div class="row align-items-center">
                                <div class="col-md-2 text-center">
                                    <div class="airline-logo">
                                       {{ $flight->airline ? substr($flight->airline->code, 0, 2) : 'XX' }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="flight-route">
                                        {{ $flight->departureAirport?->code ?? '-' }} → {{ $flight->arrivalAirport?->code ?? '-' }}
                                    </div>
                                    <div class="flight-time">
                                        {{ $flight->departure_time->format('d M Y, H:i') }} WIB
                                    </div>
                                    <div class="flight-time">
                                        <i class="fas fa-plane me-1"></i> {{ $flight->airline->name }}
                                    </div>
                                </div>
                                <div class="col-md-4 text-end">
                                    <div class="flight-price">
                                        Rp {{ number_format($flight->price, 0, ',', '.') }}
                                    </div>
                                    <a href="{{ route('bookings.selectSeat', $flight->id) }}"
                                        class="btn btn-search mt-2">
                                        Pesan Sekarang
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Airlines Partners -->
    <section class="airlines-section" id="airlines">
        <div class="container">
            <div class="section-title">
                <h2>Maskapai Partner</h2>
                <p>Bekerjasama dengan maskapai terpercaya</p>
            </div>
            <div class="row g-4">
                @foreach ($popularAirlines as $airline)
                    <div class="col-md-3 col-sm-6">
                        <div class="airline-card">
                            <div class="airline-code">{{ $airline->code }}</div>
                            <h5 class="fw-bold">{{ $airline->name }}</h5>
                            <p class="text-muted mb-0">{{ $airline->flights_count }} penerbangan</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h4 class="footer-title">
                        <i class="fas fa-plane-departure me-2"></i>SkyLine Airlines
                    </h4>
                    <p>Platform pemesanan tiket pesawat online terpercaya dengan harga terbaik dan layanan profesional.
                    </p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-md-2 mb-4">
                    <h5 class="footer-title">Produk</h5>
                    <ul class="footer-links">
                        <li><a href="#">Tiket Pesawat</a></li>
                        <li><a href="#">Promo</a></li>
                        <li><a href="#">Jadwal Penerbangan</a></li>
                    </ul>
                </div>
                <div class="col-md-2 mb-4">
                    <h5 class="footer-title">Perusahaan</h5>
                    <ul class="footer-links">
                        <li><a href="#">Tentang Kami</a></li>
                        <li><a href="#">Karir</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Partners</a></li>
                    </ul>
                </div>
                <div class="col-md-2 mb-4">
                    <h5 class="footer-title">Bantuan</h5>
                    <ul class="footer-links">
                        <li><a href="#">Pusat Bantuan</a></li>
                        <li><a href="#">Syarat & Ketentuan</a></li>
                        <li><a href="#">Kebijakan Privasi</a></li>
                        <li><a href="#">Hubungi Kami</a></li>
                    </ul>
                </div>
                <div class="col-md-2 mb-4">
                    <h5 class="footer-title">Kontak</h5>
                    <ul class="footer-links">
                        <li><i class="fas fa-phone me-2"></i>021-12345678</li>
                        <li><i class="fas fa-envelope me-2"></i>info@skyline.com</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i>Jakarta, Indonesia</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} SkyLine Airlines. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/serviceworker.js').catch(err => console.warn('SW reg failed', err));
        }
        // beforeinstallprompt placeholder
        let deferredPrompt;
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            const btn = document.createElement('button');
            btn.className = 'btn btn-gold position-fixed';
            btn.style.right = '20px';
            btn.style.bottom = '20px';
            btn.textContent = 'Install App';
            btn.addEventListener('click', async () => {
                if (!deferredPrompt) return;
                deferredPrompt.prompt();
                const choiceResult = await deferredPrompt.userChoice;
                deferredPrompt = null;
                btn.remove();
            });
            document.body.appendChild(btn);
        });
    </script>
    <script>
        function switchTab(tab) {
            // Update active tab
            document.querySelectorAll('.search-tab').forEach(t => t.classList.remove('active'));
            event.target.closest('.search-tab').classList.add('active');
        }
    </script>
</body>

</html>
