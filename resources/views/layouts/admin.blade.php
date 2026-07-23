<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - SkyLine Airlines</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="manifest" href="/manifest.webmanifest">
    <meta name="theme-color" content="#0a192f">
    <style>
        :root {
            --primary-navy: #0a192f;
            --secondary-navy: #112240;
            --gold: #f4b400;
            --gold-dark: #d49a00;
            --light-blue: #e6f1ff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
            color: #333;
            overflow-x: hidden;
        }

        .navbar-custom {
            background: linear-gradient(135deg, var(--primary-navy) 0%, var(--secondary-navy) 100%);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            padding: 12px 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
        }

        .sidebar {
            background: white;
            border-right: 1px solid #e0e0e0;
            min-height: calc(100vh - 60px);
            position: fixed;
            top: 60px;
            left: 0;
            width: 250px;
            z-index: 1020;
            overflow-y: auto;
            transition: all 0.3s;
        }

        .sidebar .nav-link {
            color: var(--primary-navy);
            font-weight: 500;
            padding: 12px 20px;
            border-radius: 8px;
            margin: 2px 8px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: linear-gradient(135deg, var(--primary-navy) 0%, #1a3a5c 100%);
            color: white;
        }

        .sidebar .nav-link.active i {
            color: var(--gold);
        }

        .sidebar .nav-link i {
            width: 20px;
            text-align: center;
        }

        .sidebar .section-title {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #999;
            padding: 15px 20px 5px;
            font-weight: 600;
        }

        .main-content {
            margin-left: 250px;
            margin-top: 60px;
            padding: 25px 30px;
            min-height: calc(100vh - 60px);
        }

        .stat-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .bg-gradient-navy {
            background: linear-gradient(135deg, var(--primary-navy) 0%, #1e3c72 100%);
        }

        .bg-gradient-emerald {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }

        .bg-gradient-gold {
            background: linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);
        }

        .bg-gradient-blue {
            background: linear-gradient(135deg, #00c6ff 0%, #0072ff 100%);
        }

        .bg-gradient-red {
            background: linear-gradient(135deg, #e52d27 0%, #b31217 100%);
        }

        .bg-gradient-purple {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
        }

        .content-card {
            background: white;
            border-radius: 15px;
            border: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .page-header {
            margin-bottom: 25px;
        }

        .page-header h2 {
            font-weight: 700;
            color: var(--primary-navy);
        }

        .page-header p {
            color: #6c757d;
            margin-bottom: 0;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-navy) 0%, #1a3a5c 100%);
            border: none;
            color: white;
            font-weight: 500;
            padding: 8px 20px;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(10, 25, 47, 0.3);
            color: white;
        }

        .btn-gold-custom {
            background: linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);
            border: none;
            color: var(--primary-navy);
            font-weight: 600;
            padding: 8px 20px;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .btn-gold-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(244, 180, 0, 0.3);
            color: var(--primary-navy);
        }

        .btn-outline-custom {
            border: 2px solid var(--primary-navy);
            color: var(--primary-navy);
            font-weight: 500;
            padding: 6px 18px;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .btn-outline-custom:hover {
            background: var(--primary-navy);
            color: white;
        }

        .table-custom {
            border-radius: 10px;
            overflow: hidden;
        }

        .table-custom thead {
            background: var(--primary-navy);
            color: white;
        }

        .table-custom thead th {
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px 15px;
            border-bottom: none;
        }

        .table-custom tbody td {
            padding: 12px 15px;
            vertical-align: middle;
        }

        .table-custom tbody tr:hover {
            background: #f8f9fa;
        }

        .badge-role {
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 12px;
        }

        .toast-container {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 9999;
        }

        .modal-custom .modal-header {
            background: var(--primary-navy);
            color: white;
        }

        .modal-custom .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 0.2rem rgba(244, 180, 0, 0.25);
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: relative;
                top: 0;
                min-height: auto;
            }

            .main-content {
                margin-left: 0;
                margin-top: 0;
                padding: 15px;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-dark navbar-custom">
        <div class="container-fluid px-4">
            <span class="navbar-brand fw-bold fs-4">
                <i class="fas fa-user-shield me-2"></i>@yield('panel_title', 'Admin Panel') - SkyLine Airlines
            </span>
            <div class="d-flex align-items-center">
                <span class="text-white me-3 d-none d-sm-inline">
                    <i class="far fa-user-circle me-1"></i>
                    @yield('role_name', 'Administrator'): <strong>{{ Auth::user()->name }}</strong>
                </span>
                <span class="badge bg-warning text-dark me-3 px-3 py-2" style="font-weight: 600;">
                    @yield('role_badge', 'Admin')
                </span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-outline-light btn-sm px-3">
                        <i class="fas fa-sign-out-alt me-1"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar p-2">
        <div class="nav flex-column">
            @yield('sidebar')
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>Terjadi kesalahan validasi. Silakan periksa input Anda.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/serviceworker.js').then(reg => {
                    // listen for updates
                    reg.addEventListener('updatefound', () => {
                        const newWorker = reg.installing;
                        newWorker.addEventListener('statechange', () => {
                            if (newWorker.state === 'installed') {
                                if (navigator.serviceWorker.controller) {
                                    // new update available
                                    console.log('New service worker installed.');
                                    newWorker.postMessage({
                                        type: 'SKIP_WAITING'
                                    });
                                }
                            }
                        });
                    });
                }).catch(err => console.warn('SW registration failed:', err));
            });
        }
    </script>
    @stack('scripts')
</body>

</html>
