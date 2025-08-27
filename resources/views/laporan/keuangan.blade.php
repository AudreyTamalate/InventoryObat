<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan</title>
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
            position: fixed; /* Agar sidebar tetap di tempatnya saat konten digulir */
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
            margin-left: 240px; /* Jarak agar tidak tertutup sidebar */
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
            flex-wrap: wrap;
            gap: 10px;
        }

        h2 {
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        th, td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }

        th {
            background-color: #f3f4f6;
            font-weight: 600;
        }

        .total-row td {
            font-weight: bold;
            background-color: #e5e7eb;
        }

        .filter-form {
            display: flex;
            gap: 20px;
            align-items: flex-end;
            flex-wrap: wrap;
        }

        .filter-form .form-group {
            display: flex;
            flex-direction: column;
            font-size: 14px;
        }

        .filter-form label {
            margin-bottom: 4px;
            color: #374151;
            font-weight: 500;
        }

        .filter-form select {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 6px 10px;
            font-size: 14px;
            min-width: 160px;
        }

        .actions {
            display: flex;
            align-items: flex-end;
            gap: 15px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .pdf-form button, .btn-pdf {
            background: #10b981;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: 0.2s;
        }

        .btn-pdf:hover {
            background: #059669;
        }

        /* Responsif untuk mobile */
        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }

            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
                border-right: none;
                border-bottom: 1px solid #e5e7eb;
            }

            .content {
                margin-left: 0;
                padding: 20px;
            }
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
            <button type="submit"
                style="background:#ef4444;color:#fff;border:none;padding:10px;border-radius:8px;width:100%;cursor:pointer;">Logout</button>
        </form>
    </div>

    <div class="content">
        <div class="breadcrumb">Laporan / Keuangan</div>
        <div class="header">
            <h2>Laporan Keuangan</h2>
            <div class="actions">
                <!-- Form untuk filter bulan, tombol "Tampilkan" dihapus -->
                <form id="filterForm" method="GET" action="{{ route('laporan.keuangan') }}" class="filter-form">
                    <div class="form-group">
                        <label for="bulan">Pilih Bulan:</label>
                        <select name="bulan" id="bulan">
                            @foreach ($daftarBulan as $bulan)
                                <option value="{{ $bulan['value'] }}" {{ $bulan['value'] == $bulanTerpilih ? 'selected' : '' }}>
                                    {{ $bulan['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>

                <form method="GET" action="{{ route('laporan.keuangan.pdf') }}" target="_blank" class="pdf-form">
                    <input type="hidden" name="bulan" value="{{ request('bulan') }}">
                    <button type="submit" class="btn-pdf">
                        <i class="fa-solid fa-file-pdf"></i> Print PDF
                    </button>
                </form>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Item Code</th>
                    <th>Nama Obat</th>
                    <th style="text-align: center;">Jumlah Keluar</th>
                    <th>Harga Beli</th>
                    <th>Total Beli</th>
                    <th>Harga Jual</th>
                    <th>Total Jual</th>
                    <th>Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalHargaBeli = 0;
                    $totalHargaJual = 0;
                    $totalPendapatan = 0;
                @endphp
                @forelse ($laporan as $item)
                    <tr>
                        <td>{{ $item->item_code }}</td>
                        <td>{{ $item->nama_obat }}</td>
                        <td style="text-align: center;">{{ $item->jumlah_keluar }}</td>
                        <td style="text-align: right;">Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                        <td style="text-align: right;">Rp {{ number_format($item->total_beli, 0, ',', '.') }}</td>
                        <td style="text-align: right;">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                        <td style="text-align: right;">Rp {{ number_format($item->total_jual, 0, ',', '.') }}</td>
                        <td style="text-align: right;">Rp {{ number_format($item->pendapatan, 0, ',', '.') }}</td>
                    </tr>
                    @php
                        $totalHargaBeli += $item->total_beli;
                        $totalHargaJual += $item->total_jual;
                        $totalPendapatan += $item->pendapatan;
                    @endphp
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center;">Belum ada data keuangan untuk bulan ini.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="4">Total Keseluruhan</td>
                    <td style="text-align: right;">Rp {{ number_format($totalHargaBeli, 0, ',', '.') }}</td>
                    <td></td>
                    <td style="text-align: right;">Rp {{ number_format($totalHargaJual, 0, ',', '.') }}</td>
                    <td style="text-align: right;">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <script>
        // Tangkap elemen dropdown bulan
        const bulanSelect = document.getElementById('bulan');

        // Tambahkan event listener untuk mendeteksi perubahan
        bulanSelect.addEventListener('change', function() {
            // Dapatkan nilai bulan yang dipilih
            const bulanTerpilih = this.value;

            // Dapatkan URL dasar (contoh: /laporan/keuangan)
            const baseUrl = window.location.pathname;

            // Buat URL baru dengan query parameter 'bulan'
            const newUrl = baseUrl + '?bulan=' + bulanTerpilih;

            // Arahkan halaman ke URL baru
            window.location.href = newUrl;
        });
    </script>
</body>
</html>
