<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Klinik XYZ</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #10b981;
            --danger-color: #ef4444;
            --bg-light: #f9f9f9;
            --bg-white: #fff;
            --border-color: #e5e7eb;
            --text-dark: #374151;
            --text-muted: #6b7280;
        }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            display: flex;
            background-color: var(--bg-light);
        }

        /* Sidebar */
        .sidebar {
            width: 240px;
            background-color: var(--bg-white);
            border-right: 1px solid var(--border-color);
            height: 100vh;
            padding: 20px 0;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 10;
        }

        .sidebar .logo {
            font-size: 18px;
            font-weight: 700;
            padding: 0 20px;
            margin-bottom: 30px;
            color: var(--text-dark);
        }

        .sidebar .menu {
            flex: 1;
        }

        .menu-title {
            font-size: 13px;
            font-weight: 700;
            color: var(--text-muted);
            margin: 15px 20px 8px;
            text-transform: uppercase;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            text-decoration: none;
            color: var(--text-dark);
            font-size: 14px;
            transition: 0.2s;
            border-radius: 8px;
            margin: 2px 10px;
        }

        .sidebar a i {
            margin-right: 10px;
            font-size: 16px;
            width: 20px;
            text-align: center;
        }

        .sidebar a:hover {
            background-color: #f3f4f6;
        }

        .sidebar a.active {
            background-color: #e5e7eb;
            font-weight: 500;
        }

        .sidebar button {
            background: var(--danger-color);
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 8px;
            width: 90%;
            cursor: pointer;
            margin: 0 auto;
            display: block;
        }

        /* Main Content */
        .main {
            flex: 1;
            padding: 30px;
            margin-left: 240px;
        }

        h1 {
            margin-bottom: 20px;
            color: var(--text-dark);
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .chart-container {
            background: var(--bg-white);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            height: 400px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .chart-container h3 {
            margin: 0 0 15px;
            text-align: center;
            color: var(--text-dark);
        }

        .summary-card {
            background: var(--bg-white);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .summary-card p {
            margin: 0;
            font-size: 14px;
            color: var(--text-muted);
        }

        .summary-card h4 {
            font-size: 24px;
            margin: 5px 0 0;
            font-weight: 700;
            color: var(--text-dark);
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }

            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
                border-right: none;
                border-bottom: 1px solid var(--border-color);
            }

            .main {
                margin-left: 0;
                padding: 20px;
            }
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">Klinik XYZ</div>

        <div class="menu">
            <div class="menu-title">Main Operational</div>
            <a href="/dashboard" class="active">
                <i class="fa-solid fa-chart-pie"></i> Dashboard
            </a>
            <a href="/obat">
                <i class="fa-solid fa-capsules"></i> Obat
            </a>
            <a href="/obat-masuk">
                <i class="fa-solid fa-box"></i> Obat Masuk
            </a>
            <a href="/obat-keluar">
                <i class="fa-solid fa-dolly"></i> Obat Keluar
            </a>

            <div class="menu-title">Report</div>
            <a href="/laporan/stok">
                <i class="fa-solid fa-chart-column"></i> Laporan Stok Obat
            </a>
            <!-- Link laporan keuangan hanya untuk kepala klinik -->
            <a href="/laporan/keuangan" id="keuangan-link" style="display:none;">
                <i class="fa-solid fa-file-invoice-dollar"></i> Laporan Keuangan
            </a>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>

    <!-- Main Content -->
    <div class="main">
        <h1>Dashboard</h1>
        <p id="welcome-message">Selamat datang!</p>

        <!-- Summary Cards (hanya kepala klinik) -->
        @if(Auth::user()->role === 'kepala_klinik')
        <div class="summary-grid">
            <div class="summary-card">
                <p>Total Pembelian Bulan Ini</p>
                <h4>Rp {{ number_format($totalPembelian, 0, ',', '.') }}</h4>
            </div>
            <div class="summary-card">
                <p>Total Penjualan Bulan Ini</p>
                <h4>Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</h4>
            </div>
        </div>
        @endif

        <!-- Charts Grid -->
        <div class="dashboard-grid">
            <!-- Expiration Pie Chart -->
            <div class="chart-container">
                <h3>Status Kedaluwarsa Obat</h3>
                <canvas id="expChart"></canvas>
            </div>

            <!-- Best-selling Chart -->
            <div class="chart-container">
                <h3>Obat Terlaris Bulan Ini</h3>
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Monthly Sales & Purchases Chart (khusus kepala klinik) -->
        @if(Auth::user()->role === 'kepala_klinik')
        <div class="chart-container" style="margin-top:20px;">
            <h3>Pembelian & Penjualan per Bulan</h3>
            <canvas id="monthlyChart"></canvas>
        </div>
        @endif

    </div>
    </div>

    <script>
        // Data dari controller Laravel
        const expData = {
            labels: ['Belum Expired', 'Sisa 6 Bulan', 'Sisa 3 Bulan', 'Sudah Expired'],
            data: [{{ $expData['belum'] }}, {{ $expData['6bulan'] }}, {{ $expData['3bulan'] }}, {{ $expData['expired'] }}],
            colors: ['#10b981', '#facc15', '#ef4444', '#1f2937']
        };

        const salesData = {
            labels: {!! json_encode($salesData->keys()) !!},
            data: {!! json_encode($salesData->values()) !!}
        };

        const monthlyData = {
            labels: {!! json_encode($monthlyData['labels']) !!},
            pembelian: {!! json_encode($monthlyData['pembelian']) !!},
            penjualan: {!! json_encode($monthlyData['penjualan']) !!}
        };

        // Data pengguna (role dari Auth)
        const userData = {
            name: "{{ Auth::user()->name }}",
            role: "{{ Auth::user()->role }}"
        };

        const welcomeEl = document.getElementById('welcome-message');
        if (userData.role === 'kepala_klinik') {
            welcomeEl.innerHTML = `Selamat datang, <b>${userData.name}</b>`;
            document.getElementById('keuangan-link').style.display = 'flex';
        } else if (userData.role === 'apoteker') {
            welcomeEl.innerHTML = `Selamat datang, <b>${userData.name}</b>`;
        }

        // Chart Expired
        new Chart(document.getElementById('expChart'), {
            type: 'pie',
            data: {
                labels: expData.labels,
                datasets: [{
                    data: expData.data,
                    backgroundColor: expData.colors,
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'right' } } }
        });

        // Chart Obat Terlaris
        new Chart(document.getElementById('salesChart'), {
            type: 'bar',
            data: {
                labels: salesData.labels,
                datasets: [{
                    label: 'Jumlah Terjual',
                    data: salesData.data,
                    backgroundColor: salesData.labels.map((_, i) => `hsl(${i * 60}, 70%, 50%)`),
                    borderRadius: 5
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
        });

        // Chart Pembelian & Penjualan Bulanan (hanya kepala klinik)
        @if(Auth::user()->role === 'kepala_klinik')
        new Chart(document.getElementById('monthlyChart'), {
            type: 'line',
            data: {
                labels: monthlyData.labels,
                datasets: [
                    { label: 'Total Pembelian', data: monthlyData.pembelian, borderColor: '#2563eb', backgroundColor: 'rgba(37, 99, 235, 0.2)', fill: true, tension: 0.3 },
                    { label: 'Total Penjualan', data: monthlyData.penjualan, borderColor: '#10b981', backgroundColor: 'rgba(16, 185, 129, 0.2)', fill: true, tension: 0.3 }
                ]
            },
            options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
        });
        @endif
    </script>
</body>

</html>
