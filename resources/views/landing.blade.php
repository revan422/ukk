<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SkyLine Airlines - Terbang ke Impianmu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        .navbar-custom { background-color: #0a192f; } /* Navy Blue */
        .text-gold { color: #f4b400; }
        .btn-gold { background-color: #f4b400; color: #0a192f; font-weight: 600; border: none; }
        .btn-gold:hover { background-color: #d49a00; color: #fff; }
        .hero-section {
            background: linear-gradient(rgba(10, 25, 47, 0.7), rgba(10, 25, 47, 0.7)), url('https://images.unsplash.com/photo-1436491865332-7a61a109cc05?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');
            background-size: cover; background-position: center; color: white; padding: 100px 0;
        }
        .card-dest { transition: transform 0.3s; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .card-dest:hover { transform: translateY(-10px); }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-custom navbar-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">✈️ SkyLine <span class="text-gold">Airlines</span></a>
            <div class="d-flex">
                <a href="{{ route('login') }}" class="btn btn-outline-light me-2">Login</a>
                <a href="{{ route('register') }}" class="btn btn-gold">Daftar</a>
            </div>
        </div>
    </nav>

    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bold">Terbang Lebih Tinggi, Lebih Nyaman</h1>
            <p class="lead mb-4">Temukan destinasi impianmu dengan harga terbaik bersama SkyLine Airlines.</p>
            <a href="{{ route('login') }}" class="btn btn-gold btn-lg px-5">Pesan Tiket Sekarang</a>
        </div>
    </section>

    <section class="container my-5">
        <h2 class="text-center mb-4" style="color: #0a192f;">Destinasi Populer</h2>
        <div class="row">
            @foreach($destinations as $dest)
            <div class="col-md-4 mb-4">
                <div class="card card-dest h-100">
                    <!-- Pastikan Anda menaruh gambar di public/images/destinations/ -->
                    <img src="{{ asset($dest->image) }}" class="card-img-top" alt="{{ $dest->title }}" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title fw-bold">{{ $dest->title }}</h5>
                        <p class="card-text text-muted">{{ $dest->description }}</p>
                        <h5 class="text-gold fw-bold">Mulai Rp {{ number_format($dest->price, 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    <!-- Your Destinations Section -->
@if($destinations->count() > 0)
<section class="destinations-section">
    <div class="container">
        <div class="section-title">
            <h2>Destinasi Pilihan</h2>
            <p>Destinasi terbaik yang kami rekomendasikan</p>
        </div>
        <div class="row">
            @foreach($destinations as $destination)
                <div class="col-md-4 col-sm-6">
                    <div class="destination-card">
                        <img src="{{ $destination->image ?? 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=400&h=300&fit=crop' }}"
                             alt="{{ $destination->name }}"
                             class="destination-img">
                        <div class="destination-overlay">
                            <h3 class="destination-name">{{ $destination->name }}</h3>
                            <p class="destination-flights">{{ $destination->description ?? 'Destinasi populer' }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Promos Section -->
@if($promos->count() > 0)
<section class="flights-section" style="background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);">
    <div class="container">
        <div class="section-title">
            <h2>🔥 Promo Spesial</h2>
            <p>Jangan lewatkan penawaran terbaik kami</p>
        </div>
        <div class="row">
            @foreach($promos as $promo)
                <div class="col-md-4">
                    <div class="flight-card" style="border: 3px solid #f4b400; background: white;">
                        <div class="text-center">
                            <h4 class="fw-bold" style="color: #f4b400;">{{ $promo->title ?? 'PROMO' }}</h4>
                            <p class="mb-2">{{ $promo->description ?? 'Penawaran spesial untuk Anda' }}</p>
                            <div class="flight-price" style="color: #d63031;">
                                Rp {{ number_format($promo->discount ?? 0, 0, ',', '.') }}
                            </div>
                            <small class="text-muted">
                                Berlaku hingga {{ \Carbon\Carbon::parse($promo->end_date)->format('d M Y') }}
                            </small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

    <footer class="bg-dark text-white text-center py-3">
        <p class="mb-0">&copy; 2026 SkyLine Airlines. All Rights Reserved.</p>
    </footer>
</body>
</html>
