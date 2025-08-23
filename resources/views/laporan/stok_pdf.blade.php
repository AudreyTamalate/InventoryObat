<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Stok Obat</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 5px;
        }

        p {
            text-align: center;
            margin: 0;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
            font-size: 11px;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        tbody tr:nth-child(even) {
            background-color: #fafafa;
        }
    </style>
</head>
<body>
    <h2>Laporan Stok Obat</h2>
    <p>Bulan: {{ $bulan ? date('F Y', strtotime($bulan)) : 'Semua' }}</p>

    <table>
        <thead>
            <tr>
                <th style="width: 80px;">Kode</th>
                <th>Nama Obat</th>
                <th style="width: 100px;">Satuan</th>
                <th style="width: 100px; text-align:right;">Stok Akhir</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($stok as $s)
                <tr>
                    <td>{{ $s->item_code }}</td>
                    <td>{{ $s->nama_obat }}</td>
                    <td>{{ $s->unit_of_measurement }}</td>
                    <td style="text-align:right;">{{ $s->stok_akhir }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align:center;">Tidak ada data stok untuk bulan ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
