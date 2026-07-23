<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Link Expired - SkyLine Airlines</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0a192f 0%, #112240 50%, #0a192f 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            color: #ccd6f6;
        }
        .card-expired {
            background: rgba(17, 34, 64, 0.95);
            border: 1px solid rgba(244, 180, 0, 0.2);
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
        }
        .text-gold { color: #f4b400 !important; }
        .btn-gold {
            background: linear-gradient(135deg, #f4b400 0%, #d49a00 100%);
            color: #0a192f;
            font-weight: 600;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        .btn-gold:hover {
            background: linear-gradient(135deg, #d49a00 0%, #b38600 100%);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(244, 180, 0, 0.4);
        }
        .expired-icon {
            font-size: 5rem;
            color: #e74c3c;
            text-shadow: 0 0 30px rgba(231, 76, 60, 0.5);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card-expired">
                    <div class="card-body p-5 text-center">
                        <div class="expired-icon">⏰</div>
                        <h3 class="fw-bold mt-3">Link Verifikasi Sudah Kedaluwarsa</h3>
                        <p class="text-muted mt-3 mb-4">
                            Link verifikasi yang Anda gunakan sudah tidak berlaku.
                            Silakan kirim ulang email verifikasi untuk mendapatkan link baru.
                        </p>

                        @if (session('message'))
                            <div class="alert alert-success" role="alert">
                                {{ session('message') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif

                        <p class="text-muted small mb-4">
                            Klik tombol di bawah untuk mengirim ulang email verifikasi ke:
                            <br>
                            <strong class="text-gold">{{ $email ?? '' }}</strong>
                        </p>

                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <input type="hidden" name="email" value="{{ $email ?? '' }}">
                            <button type="submit" class="btn btn-gold">Kirim Ulang Email Verifikasi</button>
                        </form>

                        <div class="mt-3">
                            <a href="{{ route('login') }}" class="text-white-50 small">Kembali ke halaman Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
