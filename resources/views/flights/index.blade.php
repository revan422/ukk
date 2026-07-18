<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cari Penerbangan - SkyLine Airlines</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0a192f 0%, #112240 100%);
            min-height: 100vh;
        }
        .navbar-custom {
            background: linear-gradient(135deg, #0a192f 0%, #11998e 100%);
        }
        .search-card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            margin-top: 50px;
            padding: 30px;
        }
        .btn-search {
            background: linear-gradient(135deg, #f4b400 0%, #d49a00 100%);
            color: #0a192f;
            font-weight: 700;
            border: none;
            padding: 12px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .btn-search:hover {
            background: linear-gradient(135deg, #d49a00 0%, #b38600 100%);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(244, 180, 0, 0.4);
        }
        .form-label {
            font-weight: 600;
            color: #0a192f;
        }
        .form-control, .form-select {
            border-radius: 8px;
            padding: 10px 15px;
            border: 1px solid #ddd;
        }
        .form-control:focus, .form-select:focus {
            border-color: #f4b400;
            box-shadow: 0 0 0 0.2rem rgba(244, 180, 0, 0.25);
        }
        .class-option {
            border: 2px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
        }
        .class-option:hover {
            border-color: #f4b400;
            background: rgba(244, 180, 0, 0.05);
        }
        .class-option input[type="radio"] {
            display: none;
        }
        .class-option input[type="radio"]:checked + label {
            color: #f4b400;
        }
        .class-option:has(input[type="radio"]:checked) {
            border-color: #f4b400;
            background: rgba(244, 180, 0, 0.1);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark navbar-custom">
        <div class="container px-4">
            <span class="navbar-brand fw-bold">✈️ SkyLine Airlines - Cari Penerbangan</span>
            <div>
                @auth
                    <span class="text-white me-3">Halo, {{ Auth::user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-outline-light btn-sm">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">Login</a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="search-card">
                    <h3 class="fw-bold mb-4 text-center" style="color: #0a192f;">Cari Penerbangan</h3>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('flights.search') }}" method="GET">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Dari (Bandara Asal)</label>
                                <select name="departure" class="form-select" required>
                                    <option value="">Pilih Bandara Asal</option>
                                    @foreach(\App\Models\Airport::all() as $airport)
                                        <option value="{{ $airport->id }}" {{ old('departure') == $airport->id ? 'selected' : '' }}>
                                            {{ $airport->name }} ({{ $airport->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Ke (Bandara Tujuan)</label>
                                <select name="arrival" class="form-select" required>
                                    <option value="">Pilih Bandara Tujuan</option>
                                    @foreach(\App\Models\Airport::all() as $airport)
                                        <option value="{{ $airport->id }}" {{ old('arrival') == $airport->id ? 'selected' : '' }}>
                                            {{ $airport->name }} ({{ $airport->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Tanggal</label>
                                <input type="date" name="date" class="form-control" value="{{ old('date', date('Y-m-d')) }}" required min="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12 mb-4">
                                <label class="form-label d-block mb-3">Kelas Penerbangan</label>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="class-option">
                                            <input type="radio" name="class" id="economy" value="economy" {{ old('class') == 'economy' ? 'checked' : '' }} required>
                                            <label for="economy" class="d-block">
                                                <div style="font-size: 2rem;">💺</div>
                                                <h6 class="fw-bold mb-1">Economy Class</h6>
                                                <small class="text-muted">Harga Terjangkau</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="class-option">
                                            <input type="radio" name="class" id="business" value="business" {{ old('class') == 'business' ? 'checked' : '' }}>
                                            <label for="business" class="d-block">
                                                <div style="font-size: 2rem;">💼</div>
                                                <h6 class="fw-bold mb-1">Business Class</h6>
                                                <small class="text-muted">Nyaman & Luas</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="class-option">
                                            <input type="radio" name="class" id="first" value="first" {{ old('class') == 'first' ? 'checked' : '' }}>
                                            <label for="first" class="d-block">
                                                <div style="font-size: 2rem;">👑</div>
                                                <h6 class="fw-bold mb-1">First Class</h6>
                                                <small class="text-muted">Premium & Eksklusif</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-search w-100 mt-3">
                            🔍 Cari Penerbangan
                        </button>
                    </form>
                </div>

                <!-- Info Kelas -->
                <div class="row mt-4 mb-5">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <h4>💺 Economy</h4>
                                <p class="text-muted small">Kursi standar dengan harga terjangkau. Cocok untuk perjalanan singkat.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <h4>💼 Business</h4>
                                <p class="text-muted small">Kursi lebih luas, prioritas boarding, dan layanan premium.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <h4>👑 First Class</h4>
                                <p class="text-muted small">Pengalaman mewah dengan kursi privat, makanan gourmet, dan layanan eksklusif.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
