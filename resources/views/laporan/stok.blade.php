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

        /* New CSS for the dropdown filter */
        .dropdown-container {
            position: relative;
        }

        .btn-filter {
            background: #ff9514ff;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: background 0.2s;
        }

        .btn-filter:hover {
            background: #1d4ed8;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 10px;
            background: #fff;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 15px;
            width: 250px;
            z-index: 100;
        }

        .dropdown-menu.show {
            display: block;
        }

        .dropdown-menu label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .dropdown-menu .filter-options label {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            cursor: pointer;
            font-weight: normal;
        }

        .dropdown-menu .filter-options input[type="radio"] {
            margin-right: 10px;
        }

        .dropdown-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 15px;
        }

        .btn-cancel {
            background-color: #f3f4f6;
            color: #374151;
            border: 1px solid #d1d5db;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.2s;
        }

        .btn-cancel:hover {
            background-color: #e5e7eb;
        }

        .btn-apply {
            background: #2563eb;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.2s;
        }

        .btn-apply:hover {
            background: #1d4ed8;
        }

        /* Tambahan CSS untuk warna baris */
        .yellow-row {
            background-color: #fef6a1;
        }

        .red-row {
            background-color: #fe899b;
        }

        .black-row {
            background-color: #4b5563;
            color: #fff;
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

        .black-row .edit-btn,
        .black-row .delete-btn {
            background-color: #6b7280;
        }

        .btn-excel {
            background: #3b82f6;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: 0.2s;
        }

        .btn-excel:hover {
            background: #3b82f6; 
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

            <div class="menu-title">Report</div>
            <a href="/laporan/stok" class="{{ request()->is('laporan/stok*') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-column"></i> Laporan Stok Obat
            </a>
            @auth
                @if(auth()->user()->role === 'kepala_klinik')
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
                <div class="dropdown-container">
                    <button type="button" class="btn-filter" id="dropdownFilterBtn">
                        <i class="fa-solid fa-filter"></i> Filter
                    </button>
                    <div class="dropdown-menu" id="dropdownFilterMenu">
                        <form method="GET" action="{{ route('laporan.stok') }}" class="dropdown-filter-form">
                            <label>Pilih Filter:</label>
                            <div class="filter-options">
                                <label>
                                    <input type="radio" name="filter" value="stok_terbanyak"
                                        {{ request('filter') == 'stok_terbanyak' ? 'checked' : '' }}>
                                    Stok Terbanyak
                                </label>
                                <label>
                                    <input type="radio" name="filter" value="stok_tersedikit"
                                        {{ request('filter') == 'stok_tersedikit' ? 'checked' : '' }}>
                                    Stok Tersedikit
                                </label>
                                <label>
                                    <input type="radio" name="filter" value="expire_date"
                                        {{ request('filter') == 'expire_date' ? 'checked' : '' }}>
                                    Expire Date Terdekat
                                </label>
                            </div>
                            <div class="dropdown-actions">
                                <button type="button" class="btn-cancel" id="btnCancel">Cancel</button>
                                <button type="submit" class="btn-apply">Apply</button>
                            </div>
                        </form>
                    </div>
                </div>
                <form method="GET" action="{{ route('laporan.stok.pdf') }}" target="_blank" class="pdf-form">
                    <input type="hidden" name="filter" value="{{ request('filter') }}">
                    <button type="submit" class="btn-pdf">
                        <i class="fa-solid fa-file-pdf"></i> Print PDF
                    </button>
                </form>
                <form method="GET" action="{{ route('laporan.stok.excel') }}" target="_blank" class="excel-form">
                    <input type="hidden" name="filter" value="{{ request('filter') }}">
                    <button type="submit" class="btn-excel">
                        <i class="fa-solid fa-file-excel"></i> Export Excel
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
                        $rowClass = '';
                        if ($item->expire_date) {
                            $expDate = \Carbon\Carbon::parse($item->expire_date);
                            $today = \Carbon\Carbon::now();
                            $diffInDays = $today->diffInDays($expDate, false);

                            if ($expDate->isPast()) {
                                $rowClass = 'black-row';
                            } elseif ($diffInDays <= 90 && $diffInDays >= 0) {
                                $rowClass = 'red-row';
                            } elseif ($diffInDays <= 180 && $diffInDays > 90) {
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dropdownBtn = document.getElementById('dropdownFilterBtn');
            const dropdownMenu = document.getElementById('dropdownFilterMenu');
            const cancelBtn = document.getElementById('btnCancel');
            const dropdownContainer = document.querySelector('.dropdown-container');

            dropdownBtn.addEventListener('click', function () {
                dropdownMenu.classList.toggle('show');
            });

            cancelBtn.addEventListener('click', function () {
                dropdownMenu.classList.remove('show');
            });

            document.addEventListener('click', function (event) {
                if (!dropdownContainer.contains(event.target)) {
                    dropdownMenu.classList.remove('show');
                }
            });
        });
    </script>
</body>

</html>
