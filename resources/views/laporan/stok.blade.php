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
            flex-wrap: wrap;
            gap: 10px;
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

        .filter-form input,
        .filter-form select {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 6px 10px;
            font-size: 14px;
            min-width: 160px;
        }

        .btn-tampilkan {
            background: #2563eb;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: 0.2s;
        }

        .btn-tampilkan:hover {
            background: #1d4ed8;
        }

        .actions {
            display: flex;
            align-items: flex-end;
            gap: 15px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .pdf-form button,
        .btn-pdf {
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

        .pdf-form {
            margin-top: 5px;
            /* biar sejajar */
        }

        /* Tambahan CSS untuk warna baris */
        .yellow-row {
            background-color: #fef6a1ff;
        }

        .red-row {
            background-color: #fe899bff;
        }

        .actions-btn {
            display: flex;
            gap: 5px;
        }

        .actions-btn a,
        .actions-btn button {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            text-decoration: none;
            color: white;
            transition: background-color 0.2s;
        }

        .edit-btn {
            background-color: #2563eb;
        }

        .edit-btn:hover {
            background-color: #1d4ed8;
        }

        .delete-btn {
            background-color: #ef4444;
        }

        .delete-btn:hover {
            background-color: #dc2626;
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
        <div class="breadcrumb">Laporan / Stok</div>
        <div class="header">
            <h2>Laporan Stok Obat</h2>
            <div class="actions">

                <form method="GET" action="{{ route('laporan.stok') }}" class="filter-form">

                    <div class="form-group">
                        <label for="filter">Filter:</label>
                        <select name="filter" id="filter">
                            <option value="">-- Pilih Filter --</option>
                            <option value="stok_terbanyak" {{ $filter == 'stok_terbanyak' ? 'selected' : '' }}>Stok
                                Terbanyak</option>
                            <option value="stok_tersedikit" {{ $filter == 'stok_tersedikit' ? 'selected' : '' }}>Stok
                                Tersedikit</option>
                            <option value="expire_date" {{ $filter == 'expire_date' ? 'selected' : '' }}>Expire Date
                                Terdekat
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn-tampilkan">Tampilkan</button>
                    </div>
                </form>

                <form method="GET" action="{{ route('laporan.stok.pdf') }}" target="_blank" class="pdf-form">
                    <input type="hidden" name="bulan" value="{{ request('bulan') }}">
                    <input type="hidden" name="obat" value="{{ request('obat') }}">
                    <button type="submit" class="btn-pdf">
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
                    <th>Exp Date</th>
                    @auth
                        @if(auth()->user()->role === 'kepala_klinik')
                            <th>Aksi</th>
                        @endif
                    @endauth
                </tr>
            </thead>
            <tbody>
                @forelse ($stok as $index => $item)
                    @php
                        // Inisialisasi variabel kelas
                        $rowClass = '';
                        // Periksa apakah item memiliki tanggal kedaluwarsa
                        if ($item->expire_date) {
                            $expDate = \Carbon\Carbon::parse($item->expire_date);
                            $today = \Carbon\Carbon::now();
                            $diffInMonths = $today->diffInMonths($expDate, false);

                            // Logika untuk warna
                            if ($diffInMonths <= 3 && $diffInMonths >= 0) {
                                $rowClass = 'red-row';
                            } elseif ($diffInMonths <= 6 && $diffInMonths > 3) {
                                $rowClass = 'yellow-row';
                            }
                        }
                    @endphp
                    <tr class="{{ $rowClass }}">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->item_code }}</td>
                        <td>{{ $item->nama_obat }}</td>
                        <td>{{ $item->unit_of_measurement }}</td>
                        <td>{{ $item->stok_masuk }}</td>
                        <td>{{ $item->stok_keluar }}</td>
                        <td>{{ $item->stok_akhir }}</td>
                        <td>
                            {{ $item->expire_date ? \Carbon\Carbon::parse($item->expire_date)->format('d M Y') : '-' }}
                        </td>
                        @auth
                            @if(auth()->user()->role === 'kepala_klinik')
                                <td class="actions-btn">
                                    <a href="/obat/{{ $item->item_code }}/edit" class="edit-btn">
                                        <i class="fa-solid fa-edit"></i> Edit
                                    </a>
                                    <form action="/obat/{{ $item->item_code }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-btn"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                            <i class="fa-solid fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            @endif
                        @endauth
                    </tr>
                @empty
                    <tr>
                        <td colspan="9">Belum ada data stok obat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>

</html>