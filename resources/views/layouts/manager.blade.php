<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Manager Panel') - SkyLine Airlines</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-navy: #0a192f;
            --secondary-teal: #11998e;
            --gold: #f4b400;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
            color: #333;
            overflow-x: hidden;
        }
        .navbar-custom {
            background: linear-gradient(135deg, var(--primary-navy) 0%, var(--secondary-teal) 100%);
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
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
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: linear-gradient(135deg, var(--secondary-teal) 0%, #38ef7d 100%);
            color: white;
        }
        .sidebar .nav-link i { width: 20px; text-align: center; }
        .main-content {
            margin-left: 250px;
            margin-top: 60px;
            padding: 25px 30px;
            min-height: calc(100vh - 60px);
        }
        .stat-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: transform 0.3s;
        }
        .stat-card:hover { transform: translateY(-5px); }
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
        .content-card {
            background: white;
            border-radius: 15px;
            border: none;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }
        .page-header h2 { font-weight: 700; color: var(--primary-navy); }
        .btn-pdf {
            background: linear-gradient(135deg, #e52d27 0%, #b31217 100%);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-pdf:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(229,45,39,0.4);
            color: white;
        }
        .btn-excel {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-excel:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(17,153,142,0.4);
            color: white;
        }
        .table-custom thead {
            background: var(--primary-navy);
            color: white;
        }
        .table-custom thead th {
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            padding: 12px 15px;
        }
        .table-custom tbody td { padding: 12px 15px; vertical-align: middle; }
        .table-custom tbody tr:hover { background: #f8f9fa; }
        @media (max-width: 768px) {
            .sidebar { width: 100%; position: relative; top: 0; min-height: auto; }
            .main-content { margin-left: 0; margin-top: 0; padding: 15px; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-dark navbar-custom">
        <div class="container-fluid px-4">
            <span class="navbar-brand fw-bold fs-4">
                <i class="fas fa-chart-line me-2"></i>@yield('panel_title', 'Manager Panel') - SkyLine Airlines
            </span>
            <div class="d-flex align-items-center">
                <span class="text-white me-3 d-none d-sm-inline">
                    <i class="far fa-user-circle me-1"></i>
                    Halo, <strong>{{ Auth::user()->name }}</strong>
                </span>
                <span class="badge bg-warning text-dark me-3 px-3 py-2" style="font-weight: 600;">Manager</span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-outline-light btn-sm px-3">
                        <i class="fas fa-sign-out-alt me-1"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="sidebar p-2">
        <div class="nav flex-column">
            @yield('sidebar')
        </div>
    </div>

    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
