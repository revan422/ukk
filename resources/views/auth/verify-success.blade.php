<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Berhasil - SkyLine Airlines</title>
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
        .success-card {
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
        .success-icon {
            font-size: 5rem;
            color: #28a745;
            text-shadow: 0 0 30px rgba(40, 167, 69, 0.5);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="success-card">
                    <div class="card-body p-5 text-center">
                        <div class="success-icon">✓</div>
                        <h3 class="fw-bold mt-3">Email Berhasil Diverifikasi!</h3>
                        <p class="text-muted mt-3 mb-4">
                            Terima kasih! Email Anda telah berhasil diverifikasi.
                            Sekarang Anda dapat login ke akun SkyLine Airlines Anda.
                        </p>

                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        <a href="{{ route('login') }}" class="btn btn-gold">
                            Login ke Akun Saya
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto redirect to login after 5 seconds
        setTimeout(function() {
            window.location.href = "{{ route('login') }}";
        }, 5000);
    </script>
</body>
</html>