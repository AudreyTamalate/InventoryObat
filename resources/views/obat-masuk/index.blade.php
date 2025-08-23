<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Obat Masuk</title>
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

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-primary {
            background-color: #d97706;
            color: #fff;
            text-decoration: none;
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

        .actions a {
            margin-right: 8px;
            color: #d97706;
            text-decoration: none;
            cursor: pointer;
        }

        .actions form {
            display: inline;
        }

        .actions button {
            background: none;
            border: none;
            color: red;
            cursor: pointer;
        }

        /* Modal Style */
        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            width: 400px;
            max-width: 90%;
        }

        .modal-header {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 12px;
        }

        .modal input {
            width: 100%;
            padding: 8px;
            margin-bottom: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
        }

        .btn-secondary {
            background: #ccc;
            color: #333;
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
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
        <div class="breadcrumb">Obat Masuk</div>

        <div class="header">
            <h2>Obat Masuk</h2>
            <a href="{{ route('obat-masuk.create') }}" class="btn btn-primary">Tambah Obat Masuk</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Item Code</th>
                    <th>Nama Obat</th>
                    <th>Farmasi</th>
                    <th>Batch</th>
                    <th>Harga beli</th>
                    <th>Qty Masuk</th>
                    <th>Tanggal Masuk</th>
                    <th>Expire Date</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($obatMasuk as $index => $masuk)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $masuk->item_code }}</td>
                        <td>{{ $masuk->obat->nama_obat ?? '-' }}</td>
                        <td>{{ $masuk->farmasi }}</td>
                        <td>{{ $masuk->batch }}</td>
                        <td>Rp {{ number_format($masuk->harga_beli, 0, ',', '.') }}</td>
                        <td>{{ $masuk->qty_masuk }}</td>
                        <td>{{ \Carbon\Carbon::parse($masuk->tanggal_masuk)->format('d M Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($masuk->expire_date)->format('d M Y') }}</td>
                        <td class="actions">
                            <a href="javascript:void(0)" onclick="openEditModal(
                                           '{{ $masuk->id }}',
                                           '{{ $masuk->item_code }}',
                                           '{{ $masuk->obat->nama_obat ?? '-' }}',
                                           '{{ $masuk->farmasi }}',
                                           '{{ $masuk->batch }}',
                                           '{{ $masuk->harga_beli }}',
                                           '{{ $masuk->qty_masuk }}',
                                           '{{ $masuk->tanggal_masuk }}',
                                           '{{ $masuk->expire_date }}'
                                       )" class="edit">✏ Edit</a>

                            <button type="button" class="delete"
                                onclick="openDeleteModal('{{ $masuk->id }}','{{ $masuk->item_code }}')">🗑 Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9">Data belum tersedia</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal Delete -->
    <div class="modal" id="deleteModal">
        <div class="modal-content">
            <div class="modal-header">Konfirmasi Hapus</div>
            <p id="deleteMessage">Apakah Anda yakin ingin menghapus data ini?</p>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Batal</button>
                    <button type="submit" class="btn btn-primary" style="background:#ef4444;">Hapus</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal" id="editModal">
        <div class="modal-content">
            <div class="modal-header">Edit Obat Masuk</div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')

                <label>Item Code</label>
                <input type="text" id="editItemCode" name="item_code" readonly>

                <label>Nama Obat</label>
                <input type="text" id="editNamaObat" readonly>

                <label>Farmasi</label>
                <input type="text" id="edit_farmasi" name="farmasi" readonly>

                <label>Batch</label>
                <input type="text" id="edit_batch" name="batch" required>

                <label>Harga Beli</label>
                <input type="number" id="edit_harga_beli" name="harga_beli" required>

                <label>Qty Masuk</label>
                <input type="number" id="edit_qty_masuk" name="qty_masuk" required>

                <label>Tanggal Masuk</label>
                <input type="date" id="edit_tanggal_masuk" name="tanggal_masuk" required>

                <label>Expire Date</label>
                <input type="date" id="edit_expire_date" name="expire_date" required>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openDeleteModal(id, kode) {
            document.getElementById("deleteMessage").innerText =
                "Apakah Anda yakin ingin menghapus data ini?";
            let form = document.getElementById("deleteForm");
            form.action = "/obat-masuk/" + id;
            document.getElementById("deleteModal").style.display = "flex";
        }
        function closeDeleteModal() {
            document.getElementById("deleteModal").style.display = "none";
        }

        function openEditModal(id, item_code, namaObat, farmasi, batch, harga, qty, tanggal, expire,) {
            document.getElementById("editItemCode").value = item_code;
            document.getElementById("editNamaObat").value = namaObat;
            document.getElementById("edit_farmasi").value = farmasi;
            document.getElementById("edit_batch").value = batch;
            document.getElementById("edit_harga_beli").value = harga;
            document.getElementById("edit_qty_masuk").value = qty;
            document.getElementById("edit_tanggal_masuk").value = tanggal;
            document.getElementById("edit_expire_date").value = expire;

            let form = document.getElementById("editForm");
            form.action = "/obat-masuk/" + id;

            document.getElementById("editModal").style.display = "flex";
        }

        function closeEditModal() {
            document.getElementById("editModal").style.display = "none";
        }
    </script>

</body>

</html>