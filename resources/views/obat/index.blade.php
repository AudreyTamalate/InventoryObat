<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Data Obat</title>
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
        <div class="breadcrumb">Obat</div>

        <div class="header">
            <h2>Obat</h2>
            <a href="{{ route('obat.create') }}" class="btn btn-primary">Tambah Obat</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Item Code</th>
                    <th>Nama Obat</th>
                    <th>Satuan</th>
                    <th>Produsen</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($obats as $obat)
                    <tr>
                        <td>{{ $obat->item_code }}</td>
                        <td>{{ $obat->nama_obat }}</td>
                        <td>{{ $obat->unit_of_measurement }}</td>
                        <td>{{ $obat->produsen }}</td>
                        <td class="actions">
                            <a href="javascript:void(0)" class="edit-btn"
                                onclick="openEditModal('{{ $obat->id }}','{{ $obat->item_code }}','{{ $obat->nama_obat }}','{{ $obat->unit_of_measurement }}','{{ $obat->produsen }}')">
                                <i class="fa-solid fa-edit"></i> Edit
                            </a>
                            <button type="button" class="delete-btn"
                                onclick="openDeleteModal('{{ $obat->id }}', '{{ $obat->nama_obat }}')">
                                <i class="fa-solid fa-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">Belum ada data obat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 15px;">
            {{ $obats->links() }}
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
    <div class="modal" id="editModal">
        <div class="modal-content">
            <div class="modal-header">Edit Obat</div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')

                <label>Item Code</label>
                <input type="text" id="edit_item_code" name="item_code" required>

                <label>Nama Obat</label>
                <input type="text" id="edit_nama_obat" name="nama_obat" required>

                <label>Satuan</label>
                <select id="edit_unit_of_measurement" name="unit_of_measurement" required>
                    <option value="ampul">Botol</option>
                    <option value="botol">Kapsul</option>
                    <option value="kapsul">Tablet</option>
                    <option value="tablet">Tube</option>
                </select>

                <label>Produsen</label>
                <input type="text" id="edit_produsen" name="produsen" required>


                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openDeleteModal(id, nama_obat) {
            document.getElementById("deleteMessage").innerText =
                "Apakah Anda yakin ingin menghapus data obat " + nama_obat + "?";
            let form = document.getElementById("deleteForm");
            form.action = "/obat/" + id;
            document.getElementById("deleteModal").style.display = "flex";
        }

        function closeDeleteModal() {
            document.getElementById("deleteModal").style.display = "none";
        }

        function openEditModal(id, item_code, nama_obat, unit, produsen) {
            document.getElementById("edit_item_code").value = item_code;
            document.getElementById("edit_nama_obat").value = nama_obat;
            document.getElementById("edit_unit_of_measurement").value = unit;
            document.getElementById("edit_produsen").value = produsen;

            // set action form
            let form = document.getElementById("editForm");
            form.action = "/obat/" + id;

            document.getElementById("editModal").style.display = "flex";
        }

        function closeEditModal() {
            document.getElementById("editModal").style.display = "none";
        }
    </script>


</body>

</html>