@php
    // Fallback aman jika data tidak dikirim dari Controller
    $flight = $flight ?? session('flight');
    $bookingData = $bookingData ?? session('booking_data', ['seat_number' => '-', 'price' => 0]);
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Penumpang - SkyLine Airlines</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f6f9;
            min-height: 100vh;
            color: #333;
        }

        .navbar-custom {
            background: linear-gradient(135deg, #0a192f 0%, #11998e 100%);
        }

        .form-card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: none;
        }

        .flight-info {
            background: linear-gradient(135deg, #e0f7fa 0%, #b2ebf2 100%);
            border-radius: 10px;
            padding: 20px;
            border-left: 4px solid #11998e;
        }

        .btn-gold {
            background: linear-gradient(135deg, #f4b400 0%, #d49a00 100%);
            color: #0a192f;
            font-weight: 600;
            border: none;
            padding: 12px;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .btn-gold:hover {
            background: linear-gradient(135deg, #d49a00 0%, #b38600 100%);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(244, 180, 0, 0.4);
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            padding: 10px 15px;
            border: 1px solid #ddd;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #f4b400;
            box-shadow: 0 0 0 0.2rem rgba(244, 180, 0, 0.25);
        }

        .form-label {
            font-weight: 500;
            color: #555;
        }

        .gender-options {
            display: flex;
            gap: 20px;
        }

        .gender-option {
            flex: 1;
        }

        .gender-option input[type="radio"] {
            display: none;
        }

        .gender-option label {
            display: block;
            padding: 12px;
            text-align: center;
            border: 2px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 500;
        }

        .gender-option input[type="radio"]:checked+label {
            border-color: #f4b400;
            background: rgba(244, 180, 0, 0.1);
            color: #0a192f;
        }

        .gender-option label:hover {
            border-color: #f4b400;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-dark navbar-custom">
        <div class="container px-4">
            <span class="navbar-brand fw-bold">✈️ SkyLine Airlines - Data Penumpang</span>
        </div>
    </nav>

    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="form-card">
                    <div class="card-body p-4">
                        <h3 class="fw-bold mb-4" style="color: #0a192f;">Isi Data Penumpang</h3>

                        @if($flight)
                            <!-- Info Penerbangan -->
                            <div class="flight-info mb-4">
                                <h6 class="fw-bold mb-2">Detail Penerbangan:</h6>
                                <p class="mb-1"><strong>{{ $flight->airline->name ?? 'Maskapai' }}</strong> - {{ $flight->flight_number ?? '-' }}</p>
                                <p class="mb-1">{{ $flight->departureAirport->name ?? '-' }} → {{ $flight->arrivalAirport->name ?? '-' }}</p>
                                <p class="mb-0">
                                    Kursi: <strong>{{ $bookingData['seat_number'] ?? '-' }}</strong> | 
                                    Harga: <strong>Rp {{ number_format($bookingData['price'] ?? 0, 0, ',', '.') }}</strong>
                                </p>
                            </div>

                            @if ($errors->any())
                                <div class="alert alert-danger mb-4">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('bookings.processPassenger') }}" method="POST">
                                @csrf

                                <!-- Hidden inputs -->
                                <input type="hidden" name="flight_id" value="{{ $flight->id }}">
                                <input type="hidden" name="seat_number" value="{{ $bookingData['seat_number'] ?? '' }}">
                                <input type="hidden" name="price" value="{{ $bookingData['price'] ?? 0 }}">

                                <!-- Nama Lengkap -->
                                <div class="mb-3">
                                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" name="full_name" class="form-control"
                                        value="{{ old('full_name') }}" placeholder="Sesuai KTP/Paspor" required>
                                    <small class="text-muted">Masukkan nama lengkap sesuai identitas</small>
                                </div>

                                <!-- Tanggal Lahir -->
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                    <input type="date" name="date_of_birth" class="form-control"
                                        value="{{ old('date_of_birth') }}" required>
                                    <small class="text-muted">Format: YYYY-MM-DD</small>
                                </div>

                                <!-- No NIK / Paspor -->
                                <div class="mb-3">
                                    <label class="form-label">No. NIK / Paspor <span class="text-danger">*</span></label>
                                    <input type="text" name="id_card_number" class="form-control"
                                        value="{{ old('id_card_number') }}" placeholder="Masukkan NIK atau Nomor Paspor" required>
                                    <small class="text-muted">Minimal 10 karakter</small>
                                </div>

                                <!-- Jenis Kelamin -->
                                <div class="mb-4">
                                    <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                    <div class="gender-options">
                                        <div class="gender-option">
                                            <input type="radio" name="gender" id="male" value="male"
                                                {{ old('gender') == 'male' ? 'checked' : '' }} required>
                                            <label for="male">👨 Laki-laki</label>
                                        </div>
                                        <div class="gender-option">
                                            <input type="radio" name="gender" id="female" value="female"
                                                {{ old('gender') == 'female' ? 'checked' : '' }} required>
                                            <label for="female">👩 Perempuan</label>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-gold w-100">
                                    Lanjutkan ke Pembayaran
                                </button>
                            </form>
                        @else
                            <!-- Tampilan Jika Data Flight Kosong -->
                            <div class="alert alert-warning mb-4">
                                Data penerbangan tidak ditemukan. Silakan pilih penerbangan kembali.
                            </div>
                            <a href="{{ url('/') }}" class="btn btn-gold w-100 text-center text-decoration-none">
                                🔍 Cari Penerbangan Kembali
                            </a>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
