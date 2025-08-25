<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tambah Obat Keluar</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        .form-box {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        h2 {
            margin-bottom: 20px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        label {
            font-weight: 600;
            font-size: 14px;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        select {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        .btn {
            padding: 10px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-primary {
            background-color: #d97706;
            color: #fff;
        }

        .btn-secondary {
            background-color: #e5e7eb;
            color: #333;
            text-decoration: none;
            display: inline-block;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: bold;
        }

        /* Custom combobox */
        .combobox {
            position: relative;
            width: 100%;
        }

        .combobox input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        .combobox-list {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            max-height: 200px;
            overflow-y: auto;
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 8px;
            display: none;
            z-index: 10;
        }

        .combobox-item {
            padding: 10px;
            cursor: pointer;
        }

        .combobox-item:hover {
            background: #f3f4f6;
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
            <button type="submit" style="background:#ef4444; color:#fff; border:none; padding:10px; border-radius:8px; width:100%; cursor:pointer;">Logout</button>
        </form>
    </div>


    <div class="content">
        <div class="breadcrumb">Obat Keluar > Create</div>

        <div class="form-box">
            <h2>Tambah Obat Keluar</h2>

            <form action="{{ route('obat-keluar.store') }}" method="POST">
                @csrf
                <div class="form-grid">
                    <div>
                        <label>Item Code / Nama Obat*</label>
                        <div class="combobox">
                            <input type="text" id="itemSearch" placeholder="Ketik item code atau nama obat...">
                            <div class="combobox-list" id="itemList">
                                @foreach($obats as $obat)
                                    <div class="combobox-item" data-value="{{ $obat->item_code }}">
                                        {{ $obat->item_code }} - {{ $obat->nama_obat }}
                                    </div>
                                @endforeach
                            </div>
                            <input type="hidden" name="item_code" id="itemCode">
                        </div>

                        <label>Harga Jual*</label>
                        <input type="number" name="harga_jual" placeholder="Contoh: 5000" required>

                        <label>Jumlah Keluar*</label>
                        <input type="number" name="qty_keluar" placeholder="Masukkan jumlah" required>

                        <label>Tanggal Keluar*</label>
                        <input type="date" name="tanggal_keluar" required>

                        <label>Keterangan</label>
                        <input type="text" name="keterangan" placeholder="Contoh: untuk pasien A">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="/obat-keluar" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>

    <script>
        const searchInput = document.getElementById('itemSearch');
        const itemList = document.getElementById('itemList');
        const hiddenInput = document.getElementById('itemCode');

        searchInput.addEventListener('focus', () => {
            itemList.style.display = 'block';
        });

        searchInput.addEventListener('input', () => {
            const filter = searchInput.value.toLowerCase();
            const items = itemList.querySelectorAll('.combobox-item');
            let hasVisible = false;

            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(filter)) {
                    item.style.display = 'block';
                    hasVisible = true;
                } else {
                    item.style.display = 'none';
                }
            });

            itemList.style.display = hasVisible ? 'block' : 'none';
        });

        itemList.addEventListener('click', (e) => {
            if (e.target.classList.contains('combobox-item')) {
                searchInput.value = e.target.textContent;
                hiddenInput.value = e.target.dataset.value;
                itemList.style.display = 'none';
            }
        });

        document.addEventListener('click', (e) => {
            if (!e.target.closest('.combobox')) {
                itemList.style.display = 'none';
            }
        });
    </script>
</body>

</html>
