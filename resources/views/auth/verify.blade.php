<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Email - SkyLine Airlines</title>
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
        .verify-card {
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
            padding: 10px 25px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .btn-gold:hover {
            background: linear-gradient(135deg, #d49a00 0%, #b38600 100%);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(244, 180, 0, 0.4);
        }
        .btn-gold-outline {
            background: transparent;
            color: #f4b400;
            font-weight: 600;
            border: 2px solid #f4b400;
            padding: 10px 25px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .btn-gold-outline:hover {
            background: rgba(244, 180, 0, 0.1);
            color: #ffd700;
            border-color: #ffd700;
        }
        .email-icon {
            font-size: 5rem;
            color: #f4b400;
            text-shadow: 0 0 30px rgba(244, 180, 0, 0.5);
        }
        .alert { border-radius: 8px; border: none; }
        .link-verifikasi {
            background: rgba(10, 25, 47, 0.8);
            border: 1px solid rgba(244, 180, 0, 0.3);
            border-radius: 8px;
            padding: 15px;
            word-break: break-all;
            font-size: 0.8rem;
            color: #64ffda;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="verify-card">
                    <div class="card-body p-5 text-center">
                        <div class="email-icon">📧</div>
                        <h3 class="fw-bold mt-3">Verifikasi Email Anda</h3>
                        <p class="text-muted mt-3">
                            Terima kasih telah mendaftar! Sebelum melanjutkan, mohon verifikasi email Anda dengan mengklik link yang telah kami kirimkan ke email Anda.
                        </p>

                        @if(Auth::check())
                            <p class="text-gold fw-bold fs-5">{{ Auth::user()->email }}</p>
                        @endif

                        @if (session('message'))
                            <div class="alert alert-success" role="alert">
                                {{ session('message') }}
                            </div>
                        @endif

                        @if (session('verification_url'))
                            <div class="alert alert-info mt-3">
                                <strong>🔗 Link Verifikasi (klik langsung):</strong><br>
                                <a href="{{ session('verification_url') }}" class="btn btn-sm btn-gold mt-2">
                                    Klik untuk Verifikasi Email
                                </a>
                            </div>
                        @endif

                        <p class="text-muted small">
                            Jika Anda tidak menerima email, klik tombol di bawah untuk mengirim ulang:
                        </p>

                        <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                            @csrf
                            <button type="submit" class="btn btn-gold">Kirim Ulang Email Verifikasi</button>
                        </form>

                        <form class="d-inline mt-2" method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-link text-white-50">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>