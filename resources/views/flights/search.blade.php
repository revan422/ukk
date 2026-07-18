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
        }
        .btn-search:hover {
            background: linear-gradient(135deg, #d49a00 0%, #b38600 100%);
            color: #fff;
        }
        .form-label {
            font-weight: 600;
            color: #0a192f;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark navbar-custom">
        <div class="container px-4">
            <span class="navbar-brand fw-bold">✈️ SkyLine Airlines - Cari Penerbangan</span>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="search-card">
                    <h3 class="fw-bold mb-4 text-center" style="color: #0a192f;">Cari Penerbangan</h3>

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
                                <input type="date" name="date" class="form-control" value="{{ old('date', date('Y-m-d')) }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kelas Penerbangan</label>
                                <select name="class" class="form-select" required>
                                    <option value="">Pilih Kelas</option>
                                    <option value="economy" {{ old('class') == 'economy' ? 'selected' : '' }}>
                                        💺 Economy Class (Termurah)
                                    </option>
                                    <option value="business" {{ old('class') == 'business' ? 'selected' : '' }}>
                                        💼 Business Class (Nyaman)
                                    </option>
                                    <option value="first" {{ old('class') == 'first' ? 'selected' : '' }}>
                                        👑 First Class (Premium)
                                    </option>
                                </select>
                                <small class="text-muted">Pilih kelas sesuai kebutuhan Anda</small>
                            </div>

                            <div class="col-md-6 mb-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-search w-100">
                                    🔍 Cari Penerbangan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
