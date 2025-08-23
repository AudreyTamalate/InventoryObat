<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Laporan Stok Obat</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            font-family: 'Inter', sans-serif;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            display: flex;
            background: #f9f9f9;
        }

        .sidebar {
            width: 240px;
            background-color: #fff;
            border-right: 1px solid #e5e7eb;
            height: 100vh;
            padding: 20px 0;
            display: flex;
            flex-direction: column;
        }

        .sidebar .logo {
            font-size: 18px;
            font-weight: bold;
            padding: 0 20px;
            margin-bottom: 30px;
        }

        .sidebar .menu {
            flex: 1;
        }

        .menu-title {
            font-size: 13px;
            font-weight: bold;
            color: #6b7280;
            margin: 15px 20px 8px;
            text-transform: uppercase;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            text-decoration: none;
            color: #374151;
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
            font-weight: bold;
        }

        .content {
            flex: 1;
            padding: 40px;
        }

        .breadcrumb {
            font-size: 14px;
            color: #888;
            margin-bottom: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        th,
        td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }

        th {
            background-color: #f3f4f6;
            font-weight: 600;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <div class="logo">Klinik XYZ</div>

        <div class="menu">
            <div class="menu-title">Main Operational</div>
            <a href="/dashboard" class="{{ request()->is('dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-pie"></i> Dashboard
            </a>
            <a href="/obat" class="{{ request()->is('obat') ? 'active' : '' }}">
                <i class="fa-solid fa-capsules"></i> Obat
            </a>
            <a href="/obat-masuk" class="{{ request()->is('obat-masuk*') ? 'active' : '' }}">
                <i class="fa-solid fa-box"></i> Obat Masuk
            </a>
            <a href="/obat-keluar" class="{{ request()->is('obat-keluar*') ? 'active' : '' }}">
                <i class="fa-solid fa-dolly"></i> Obat Keluar
            </a>

            @auth
                @if(auth()->user()->role === 'kepala_klinik')
                    <div class="menu-title">Report</div>
                    <a href="/laporan/stok" class="{{ request()->is('laporan/stok*') ? 'active' : '' }}">
                        <i class="fa-solid fa-chart-column"></i> Laporan Stok Obat
                    </a>
                    <a href="/laporan/keuangan" class="{{ request()->is('laporan/keuangan*') ? 'active' : '' }}">
                        <i class="fa-solid fa-file-invoice-dollar"></i> Laporan Keuangan
                    </a>
                @endif
            @endauth
        </div>

        <form method="POST" action="{{ route('logout') }}" style="padding: 0 20px; margin-top: auto;">
            @csrf
            <button type="submit" style="
                background:#ef4444; 
                color:#fff; 
                border:none; 
                padding:10px; 
                border-radius:8px; 
                width:100%; 
                cursor:pointer;">Logout</button>
        </form>
    </div>

    <div class="content">
        <div class="breadcrumb">Laporan / Stok</div>

        <div class="header">
            <h2>Laporan Stok Obat</h2>
            <div>
                <form method="GET" action="{{ route('laporan.stok') }}"
                    style="display:inline-block; margin-right:10px;">
                    <label for="bulan">Filter Bulan:</label>
                    <input type="month" id="bulan" name="bulan" value="{{ request('bulan') }}">
                    <button type="submit" style="
                        background:#3b82f6; 
                        color:#fff; 
                        border:none; 
                        padding:6px 12px; 
                        border-radius:6px; 
                        cursor:pointer;">
                        <i class="fa-solid fa-filter"></i> Filter
                    </button>
                </form>

                <form method="GET" action="{{ route('laporan.stok.pdf') }}" target="_blank"
                    style="display:inline-block;">
                    <input type="hidden" name="bulan" value="{{ request('bulan') }}">
                    <button type="submit" style="
                        background:#10b981; 
                        color:#fff; 
                        border:none; 
                        padding:6px 12px; 
                        border-radius:6px; 
                        cursor:pointer;">
                        <i class="fa-solid fa-file-pdf"></i> Print PDF
                    </button>
                </form>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Item Code</th>
                    <th>Nama Obat</th>
                    <th>Satuan</th>
                    <th>Stok Masuk</th>
                    <th>Stok Keluar</th>
                    <th>Stok Akhir</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($stok as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->item_code }}</td>
                        <td>{{ $item->nama_obat }}</td>
                        <td>{{ $item->unit_of_measurement }}</td>
                        <td>{{ $item->stok_masuk }}</td>
                        <td>{{ $item->stok_keluar }}</td>
                        <td>{{ $item->stok_akhir }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">Belum ada data stok obat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</body>
</html>
