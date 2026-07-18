<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Atur Ulang Password - SkyLine Airlines</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0a192f 0%, #112240 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            color: #ccd6f6;
        }
        .reset-card {
            background: rgba(17, 34, 64, 0.95);
            border: 1px solid rgba(244, 180, 0, 0.2);
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
            max-width: 450px;
            margin: 0 auto;
        }
        .text-gold { color: #f4b400 !important; }
        .btn-gold {
            background: linear-gradient(135deg, #f4b400 0%, #d49a00 100%);
            color: #0a192f; font-weight: 600; border: none; padding: 12px; border-radius: 8px;
            transition: all 0.3s;
        }
        .btn-gold:hover {
            background: linear-gradient(135deg, #d49a00 0%, #b38600 100%);
            color: #fff; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(244, 180, 0, 0.4);
        }
        .form-control {
            background: rgba(10, 25, 47, 0.6); border: 1px solid rgba(244, 180, 0, 0.3);
            color: #ccd6f6; padding: 10px 15px; border-radius: 8px;
        }
        .form-control:focus {
            background: rgba(10, 25, 47, 0.8); border-color: #f4b400; color: #fff;
            box-shadow: 0 0 0 0.2rem rgba(244, 180, 0, 0.25);
        }
        .form-label { color: #8892b0; font-weight: 500; font-size: 0.9rem; }
        .logo-plane { font-size: 3rem; color: #f4b400; text-shadow: 0 0 20px rgba(244, 180, 0, 0.5); }
        a { color: #f4b400; text-decoration: none; font-weight: 500; }
        a:hover { color: #ffd700; text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="reset-card">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <div class="logo-plane">🔑</div>
                            <h3 class="fw-bold mt-2">Atur Ulang Password</h3>
                            <p class="text-muted small">
                                Silakan buat password baru Anda di bawah ini
                            </p>
                        </div>

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0 small">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('password.update') }}" method="POST">
                            @csrf

                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="mb-3">
                                <label class="form-label">Alamat Email</label>
                                <input type="email" name="email" class="form-control" value="{{ $email ?? old('email') }}" required readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password Baru</label>
                                <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required autofocus>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru" required>
                            </div>

                            <button type="submit" class="btn btn-gold w-100 mb-3">Simpan Password Baru</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
