<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $roleInfo['title'] }} - SkyLine Airlines</title>
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
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="nama@email.com" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
                            </div>

                             <!-- CAPTCHA -->
                             <div class="mb-4 d-flex justify-content-center">
                                 <div class="recaptcha-widget-container d-flex align-items-center justify-content-between p-3" style="background: #f9f9f9; border: 1px solid #d3d3d3; border-radius: 4px; color: #000; width: 100%; max-width: 304px; box-shadow: 0 0 4px rgba(0,0,0,0.08);">
                                     <div class="d-flex align-items-center gap-3">
                                         <!-- Checkbox Wrapper -->
                                         <div class="position-relative" style="width: 28px; height: 28px;">
                                             <!-- Hidden native checkbox -->
                                             <input type="checkbox" id="captcha-checkbox" name="captcha_checked" value="1" required style="position: absolute; opacity: 0; cursor: pointer; height: 0; width: 0;">
                                             <!-- Custom Styled Checkbox -->
                                             <div id="captcha-box-styled" onclick="handleCaptchaClick()" style="width: 28px; height: 28px; border: 2px solid #c1c1c1; border-radius: 2px; background: #fff; cursor: pointer; transition: background 0.2s, border-color 0.2s; display: flex; align-items: center; justify-content: center;">
                                                 <!-- Checkmark Icon (initially hidden) -->
                                                 <svg id="captcha-check-icon" style="display: none; width: 18px; height: 18px; fill: none; stroke: #009d57; stroke-width: 3; stroke-linecap: round; stroke-linejoin: round;" viewBox="0 0 24 24">
                                                     <polyline points="20 6 9 17 4 12"></polyline>
                                                 </svg>
                                                 <!-- Spinner Icon (initially hidden) -->
                                                 <div id="captcha-spinner" class="spinner-border text-primary" role="status" style="display: none; width: 20px; height: 20px; border-width: 2px; color: #f4b400 !important;">
                                                     <span class="visually-hidden">Loading...</span>
                                                 </div>
                                             </div>
                                         </div>
                                         <label for="captcha-checkbox" class="mb-0 fw-semibold" style="font-family: Roboto, Helvetica, Arial, sans-serif; font-size: 14px; color: #2d2d2d; cursor: pointer; user-select: none;">Saya bukan robot</label>
                                     </div>
                                     
                                     <div class="d-flex flex-column align-items-center" style="font-family: Roboto, Helvetica, Arial, sans-serif; font-size: 8px; color: #9b9b9b; line-height: 1; margin-left: 20px;">
                                         <!-- reCAPTCHA logo -->
                                         <svg viewBox="0 0 24 24" style="width: 26px; height: 26px; margin-bottom: 2px;">
                                             <path fill="#4285F4" d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M12,4A8,8 0 0,1 20,12C20,13.25 19.72,14.44 19.22,15.5L17.47,13.75C17.81,13.2 18,12.62 18,12A6,6 0 0,0 12,6V4M12,8A4,4 0 0,1 16,12C16,12.62 15.81,13.2 15.47,13.75L13.72,12C13.88,11.83 14,11.63 14,11.41C14,11.08 13.81,10.8 13.5,10.63L11.75,8.88C11.83,8.88 11.91,8.88 12,8.88M12,10A2,2 0 0,1 14,12A2,2 0 0,1 12,14A2,2 0 0,1 10,12A2,2 0 0,1 12,10M6,12A6,6 0 0,0 12,18V20A8,8 0 0,1 4,12C4,10.75 4.28,9.56 4.78,8.5L6.53,10.25C6.19,10.8 6,11.38 6,12M12,16A4,4 0 0,1 8,12C8,11.38 8.19,10.8 8.53,10.25L10.28,12C10.12,12.17 10,12.37 10,12.59C10,12.92 10.19,13.2 10.5,13.37L12.25,15.12C12.17,15.12 12.09,15.12 12,15.12M12,14A2,2 0 0,1 10,12A2,2 0 0,1 12,10A2,2 0 0,1 14,12A2,2 0 0,1 12,14Z"/>
                                         </svg>
                                         <span style="font-weight: 700; color: #555;">reCAPTCHA</span>
                                         <span style="margin-top: 2px; font-size: 7px;"><a href="#" style="color: #9b9b9b; text-decoration: none;">Privasi</a> - <a href="#" style="color: #9b9b9b; text-decoration: none;">Persyaratan</a></span>
                                     </div>
                                 </div>
                             </div>
                             <!-- END CAPTCHA -->

                             <button type="submit" class="btn btn-gold w-100">Daftar Sekarang</button>
                         </form>

                         <p class="text-center mt-4 small">
                             Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a>
                         </p>
                     </div>
                 </div>
             </div>
         </div>
     </div>

     <script>
         let captchaVerified = false;

         function handleCaptchaClick() {
             if (captchaVerified) return;

             const checkbox = document.getElementById('captcha-checkbox');
             const boxStyled = document.getElementById('captcha-box-styled');
             const checkIcon = document.getElementById('captcha-check-icon');
             const spinner = document.getElementById('captcha-spinner');

             // Show spinner
             boxStyled.style.borderColor = '#f4b400';
             spinner.style.display = 'block';

             // Perform AJAX request to verify
             fetch('{{ route("captcha.verifyCheckbox") }}', {
                 method: 'POST',
                 headers: {
                     'Content-Type': 'application/json',
                     'X-CSRF-TOKEN': '{{ csrf_token() }}'
                 },
                 body: JSON.stringify({})
             })
             .then(response => response.json())
             .then(data => {
                 if (data.success) {
                     setTimeout(() => {
                         spinner.style.display = 'none';
                         checkIcon.style.display = 'block';
                         boxStyled.style.border = 'none';
                         boxStyled.style.background = 'transparent';
                         checkbox.checked = true;
                         captchaVerified = true;
                     }, 1000); // simulate delay like real recaptcha
                 } else {
                     spinner.style.display = 'none';
                     boxStyled.style.borderColor = '#d93025';
                 }
             })
             .catch(error => {
                 console.error('Captcha error:', error);
                 spinner.style.display = 'none';
                 boxStyled.style.borderColor = '#d93025';
             });
         }
     </script>
 </body>
 </html>
