<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Obat Keluar</title>
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

        /* CSS yang dimodifikasi untuk tombol Aksi */
        .actions {
            display: flex;
            gap: 5px;
        }

        .actions a,
        .actions button {
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

        /* End of modified CSS */

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
        <div class="breadcrumb">Obat Keluar</div>

        <div class="header">
            <h2>Obat Keluar</h2>
            <a href="{{ route('obat-keluar.create') }}" class="btn btn-primary">Tambah Obat Keluar</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Item Code</th>
                    <th>Nama Obat</th>
                    <th>Harga Jual</th>
                    <th>Qty Keluar</th>
                    <th>Tanggal Keluar</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($obatKeluars as $index => $keluar)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $keluar->item_code }}</td>
                        <td>{{ $keluar->obat->nama_obat ?? '-' }}</td>
                        <td>Rp {{ number_format($keluar->harga_jual, 0, ',', '.') }}</td>
                        <td>{{ $keluar->qty_keluar }}</td>
                        <td>{{ \Carbon\Carbon::parse($keluar->tanggal_keluar)->format('d M Y') }}</td>
                        <td>{{ $keluar->keterangan ?? '-' }}</td>
                        <td class="actions">
                            <a href="javascript:void(0)" class="edit-btn" onclick="openEditModal(
                                           {{ $keluar->id }}, 
                                           '{{ $keluar->item_code }}', 
                                           '{{ $keluar->obat->nama_obat ?? '-' }}',
                                           '{{ $keluar->harga_jual }}',
                                           '{{ $keluar->qty_keluar }}',
                                           '{{ \Carbon\Carbon::parse($keluar->tanggal_keluar)->format('Y-m-d') }}',
                                           '{{ $keluar->keterangan ?? '' }}')">
                                <i class="fa-solid fa-edit"></i> Edit
                            </a>
                            <button class="delete-btn" onclick="openDeleteModal({{ $keluar->id }})">
                                <i class="fa-solid fa-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">Data belum tersedia</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 15px;">
            {{ $obatKeluars->links() }}
        </div>


    </div>

    <div class="modal" id="editModal">
        <div class="modal-content">
            <div class="modal-header">Edit Obat Keluar</div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')

                <label>Item Code</label>
                <input type="text" id="editItemCode" name="item_code" readonly>

                <label>Nama Obat</label>
                <input type="text" id="editNamaObat" readonly>

                <label>Harga Jual</label>
                <input type="number" id="editHarga" name="harga_jual" required>

                <label>Qty Keluar</label>
                <input type="number" name="qty_keluar" id="editQty" required>

                <label>Tanggal Keluar</label>
                <input type="date" name="tanggal_keluar" id="editTanggal" required>

                <label>Keterangan</label>
                <input type="text" name="keterangan" id="editKeterangan">

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('editModal')">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>


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

    <script>
        function openEditModal(id, itemCode, namaObat, harga, qty, tanggal, ket) {
            // Tampilkan modal
            document.getElementById('editModal').style.display = 'flex';

            // Isi form dengan data dari tabel
            document.getElementById('editItemCode').value = itemCode;
            document.getElementById('editNamaObat').value = namaObat;
            document.getElementById('editHarga').value = harga;
            document.getElementById('editQty').value = qty;
            document.getElementById('editTanggal').value = tanggal;
            document.getElementById('editKeterangan').value = ket;

            // Ubah action form sesuai id yang dipilih
            document.getElementById('editForm').action = '/obat-keluar/' + id;
        }

        function openDeleteModal(id) {
            document.getElementById("deleteMessage").innerText =
                "Apakah Anda yakin ingin menghapus data ini?";
            let form = document.getElementById("deleteForm");
            form.action = "/obat-keluar/" + id;
            document.getElementById("deleteModal").style.display = "flex";
        }

        function closeDeleteModal() {
            document.getElementById("deleteModal").style.display = "none";
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
    </script>
</body>

</html>