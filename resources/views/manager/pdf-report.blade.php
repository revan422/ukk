<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Manajemen - SkyLine Airlines</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #0a192f;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #0a192f;
            font-size: 24px;
        }
        .header h2 {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 16px;
            font-weight: normal;
        }
        .summary-box {
            background: #f4f6f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .summary-box h3 {
            margin: 0 0 10px 0;
            color: #0a192f;
            font-size: 16px;
        }
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .stat-item {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            padding: 15px;
            background: #f8f9fa;
            border: 1px solid #ddd;
        }
        .stat-label {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .stat-value {
            font-size: 20px;
            font-weight: bold;
            color: #0a192f;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th {
            background: #0a192f;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
        }
        table td {
            padding: 8px 10px;
            border-bottom: 1px solid #ddd;
            font-size: 11px;
        }
        table tr:nth-child(even) {
            background: #f8f9fa;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .section-title {
            background: #0a192f;
            color: white;
            padding: 8px 12px;
            margin: 20px 0 10px 0;
            font-size: 14px;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #0a192f;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>SKYLINE AIRLINES</h1>
        <h2>Laporan Manajemen & Analisis Bisnis</h2>
        <p style="margin: 5px 0 0 0; color: #999;">Dicetak pada: {{ date('d F Y H:i:s') }}</p>
    </div>

    <!-- Summary Statistics -->
    <div class="summary-box">
        <h3>📊 Ringkasan Statistik</h3>
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-label">Total Pendapatan</div>
                <div class="stat-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Total Booking</div>
                <div class="stat-value">{{ $totalBookings }} transaksi</div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Rata-rata Harga Tiket</div>
                <div class="stat-value">Rp {{ number_format($avgTicketPrice, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <!-- Pendapatan per Maskapai -->
    <div class="section-title">💰 Pendapatan per Maskapai</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Maskapai</th>
                <th class="text-center">Total Booking</th>
                <th class="text-right">Total Pendapatan</th>
                <th class="text-right">Persentase</th>
            </tr>
        </thead>
        <tbody>
            @php $totalRev = $revenueByAirline->sum('total_revenue'); @endphp
            @foreach($revenueByAirline as $index => $airline)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td><strong>{{ $airline->airline_name }}</strong></td>
                <td class="text-center">{{ $airline->total_bookings }}</td>
                <td class="text-right">Rp {{ number_format($airline->total_revenue, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format(($airline->total_revenue / $totalRev) * 100, 1) }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Tren Booking per Bulan -->
    <div class="section-title">📈 Tren Booking 6 Bulan Terakhir</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Bulan</th>
                <th class="text-center">Total Booking</th>
                <th class="text-right">Total Pendapatan</th>
                <th class="text-right">Rata-rata per Booking</th>
            </tr>
        </thead>
        <tbody>
            @foreach($monthlyBookings as $index => $month)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    @php
                        $monthName = \Carbon\Carbon::createFromFormat('Y-m', $month->month)->format('F Y');
                        $monthNames = [
                            'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret',
                            'April' => 'April', 'May' => 'Mei', 'June' => 'Juni',
                            'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September',
                            'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
                        ];
                        echo $monthNames[date('F', strtotime($month->month))] . ' ' . date('Y', strtotime($month->month));
                    @endphp
                </td>
                <td class="text-center">{{ $month->total }}</td>
                <td class="text-right">Rp {{ number_format($month->revenue, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($month->total > 0 ? $month->revenue / $month->total : 0, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Top Rute Terlaris -->
    <div class="section-title">🏆 Top 5 Rute Terlaris</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Rute Penerbangan</th>
                <th class="text-center">Total Booking</th>
                <th class="text-right">Kontribusi</th>
            </tr>
        </thead>
        <tbody>
            @php $totalBookingsRoutes = $topRoutes->sum('total_bookings'); @endphp
            @foreach($topRoutes as $index => $route)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td><strong>{{ $route->from_code }} → {{ $route->to_code }}</strong></td>
                <td class="text-center">{{ $route->total_bookings }}</td>
                <td class="text-right">{{ number_format(($route->total_bookings / $totalBookingsRoutes) * 100, 1) }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p><strong>Laporan ini dibuat secara otomatis oleh sistem SkyLine Airlines</strong></p>
        <p>Untuk informasi lebih lanjut, hubungi manajemen di manajemen@skyline-airlines.com</p>
        <p>&copy; {{ date('Y') }} SkyLine Airlines. All rights reserved.</p>
    </div>
</body>
</html>
