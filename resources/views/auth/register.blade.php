<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $roleInfo['title'] }} - SkyLine Airlines</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0a192f 0%, #112240 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            color: #ccd6f6;
        }
        .register-card {
            background: rgba(17, 34, 64, 0.95);
            border: 1px solid rgba(244, 180, 0, 0.2);
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
            max-width: 500px;
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
        .btn-gold:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
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
        .role-badge {
            display: inline-block;
            padding: 8px 20px;
            background: rgba(244, 180, 0, 0.2);
            border: 2px solid rgba(244, 180, 0, 0.5);
            border-radius: 25px;
            color: #f4b400;
            font-weight: 700;
            font-size: 16px;
            margin-bottom: 20px;
        }
        .g-recaptcha {
            display: flex;
            justify-content: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="register-card">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <div class="logo-plane">✈️</div>
                            <h3 class="fw-bold mt-2">Bergabung dengan <span class="text-gold">SkyLine</span></h3>
                            <p class="text-muted small">{{ $roleInfo['desc'] }}</p>

                            <!-- Badge Role -->
                            <div class="role-badge">
                                {{ $roleInfo['icon'] }} {{ $roleInfo['title'] }}
                            </div>
                        </div>

                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0 small">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('register') }}" method="POST">
                            @csrf

                            <!-- Hidden input untuk role (otomatis terisi berdasarkan URL) -->
                            <input type="hidden" name="role" value="{{ $role }}">

                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="nama@email.com" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 8 karakter" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
                            </div>

                             <!-- Google reCAPTCHA -->
                             <div class="mb-4">
                                 <div class="g-recaptcha" data-sitekey="{{ $siteKey }}"></div>
                                 @error('g-recaptcha-response')
                                     <div class="text-danger small mt-1">{{ $message }}</div>
                                 @enderror
                             </div>

                             <button type="submit" class="btn btn-gold w-100" id="register-btn">Daftar Sekarang</button>
                         </form>

                         <p class="text-center mt-4 small">
                             Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a>
                         </p>
                     </div>
                 </div>
             </div>
         </div>
     </div>
</body>
</html>
