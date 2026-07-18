<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Akun - SkyLine Airlines</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-navy: #0a192f;
            --secondary-navy: #112240;
            --gold: #f4b400;
            --gold-dark: #d49a00;
        }

        body { font-family: 'Poppins', sans-serif; background-color: #f4f6f9; color: #333; }
        .navbar-custom {
            background: linear-gradient(135deg, var(--primary-navy) 0%, var(--secondary-navy) 100%);
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }
        .navbar-brand { font-size: 24px; font-weight: 800; color: white !important; }
        .navbar-brand span { color: var(--gold); }
        .profile-container { margin-top: 40px; margin-bottom: 60px; }
        .nav-pills-custom .nav-link {
            color: var(--primary-navy);
            font-weight: 600;
            padding: 12px 20px;
            border-radius: 10px;
            margin-bottom: 8px;
            text-align: left;
            transition: all 0.3s;
            background: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.02);
            border: 1px solid #e0e0e0;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .nav-pills-custom .nav-link:hover {
            background-color: rgba(244, 180, 0, 0.05);
            border-color: var(--gold);
        }
        .nav-pills-custom .nav-link.active {
            background: linear-gradient(135deg, var(--primary-navy) 0%, var(--secondary-navy) 100%);
            color: white;
            border-color: var(--primary-navy);
        }
        .nav-pills-custom .nav-link.active i { color: var(--gold); }
        .settings-card {
            background: white;
            border-radius: 20px;
            border: none;
            box-shadow: 0 8px 30px rgba(0,0,0,0.05);
            padding: 30px;
        }
        .form-label { font-weight: 600; color: var(--primary-navy); }
        .form-control, .form-select {
            border-radius: 8px;
            padding: 10px 15px;
            border: 2px solid #e0e0e0;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 0.2rem rgba(244, 180, 0, 0.15);
        }
        .btn-gold {
            background: linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);
            color: var(--primary-navy);
            border: none;
            font-weight: 700;
            padding: 12px 25px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        .btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(244, 180, 0, 0.3);
            color: var(--primary-navy);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark navbar-custom py-3">
        <div class="container">
            <a class="navbar-brand" href="{{ route('landing') }}">
                <i class="fas fa-plane-departure me-2"></i>
                Sky<span>Line</span> Airlines
            </a>
            <div class="d-flex align-items-center">
                <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-sm px-3 me-2">
                    <i class="fas fa-arrow-left me-1"></i> Dashboard
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-outline-light btn-sm px-3"><i class="fas fa-sign-out-alt me-1"></i> Keluar</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container profile-container">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="fw-bold mb-1" style="color: #0a192f;">Pengaturan Akun</h2>
                <p class="text-muted">Kelola data pribadi dan keamanan akun Anda.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="border-radius: 12px;">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="border-radius: 12px;">
                <i class="fas fa-exclamation-triangle me-2"></i> Mohon periksa kembali inputan Anda:
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-3">
                <div class="nav flex-column nav-pills nav-pills-custom" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <button class="nav-link active" id="v-pills-personal-tab" data-bs-toggle="pill" data-bs-target="#v-pills-personal" type="button" role="tab">
                        <i class="fas fa-user-circle"></i> Data Pribadi
                    </button>
                    <button class="nav-link" id="v-pills-security-tab" data-bs-toggle="pill" data-bs-target="#v-pills-security" type="button" role="tab">
                        <i class="fas fa-user-lock"></i> Keamanan Akun
                    </button>
                </div>
            </div>

            <div class="col-md-9">
                <div class="tab-content" id="v-pills-tabContent">
                    <!-- DATA PRIBADI -->
                    <div class="tab-pane fade show active" id="v-pills-personal" role="tabpanel">
                        <div class="settings-card">
                            <h4 class="fw-bold mb-4" style="color: #0a192f;"><i class="fas fa-user me-2 text-warning"></i> Data Pribadi</h4>
                            <form action="{{ route('profile.settings.update') }}" method="POST">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label">Nama Lengkap</label>
                                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Tanggal Lahir</label>
                                        <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Jenis Kelamin</label>
                                        <select name="gender" class="form-select">
                                            <option value="">Pilih Jenis Kelamin</option>
                                            <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Paspor / NIK</label>
                                        <input type="text" name="passport_number" class="form-control" value="{{ old('passport_number', $user->passport_number) }}" placeholder="Nomor paspor atau NIK">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Alamat Email</label>
                                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-gold mt-4"><i class="fas fa-save me-1"></i> Simpan Data</button>
                            </form>
                        </div>
                    </div>

                    <!-- KEAMANAN AKUN -->
                    <div class="tab-pane fade" id="v-pills-security" role="tabpanel">
                        <div class="settings-card">
                            <h4 class="fw-bold mb-4" style="color: #0a192f;"><i class="fas fa-user-lock me-2 text-warning"></i> Keamanan Akun</h4>
                            <form action="{{ route('profile.settings.security') }}" method="POST">
                                @csrf
                                <h5 class="fw-bold mb-3 text-dark"><i class="fas fa-key me-2 text-primary"></i> Ubah Kata Sandi</h5>
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label">Kata Sandi Saat Ini</label>
                                        <input type="password" name="current_password" class="form-control" placeholder="Masukkan kata sandi lama" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Kata Sandi Baru</label>
                                        <input type="password" name="new_password" class="form-control" placeholder="Minimal 8 karakter" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Konfirmasi Kata Sandi Baru</label>
                                        <input type="password" name="new_password_confirmation" class="form-control" placeholder="Ulangi kata sandi baru" required>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-gold mt-4"><i class="fas fa-save me-1"></i> Simpan Password</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
