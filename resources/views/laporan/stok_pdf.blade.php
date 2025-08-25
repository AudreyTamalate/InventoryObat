<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Stok Obat</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            /* Mengecilkan ukuran font dasar */
            margin: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 5px;
        }

        p {
            text-align: center;
            margin: 0;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px 5px;
            /* Mengurangi padding */
            text-align: left;
            font-size: 9px;
            /* Mengecilkan font di dalam sel */
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            font-size: 9.5px;
            /* Mengecilkan font di header */
        }

        tbody tr:nth-child(even) {
            background-color: #fafafa;
        }
    </style>
</head>

<body>
    <h2>Laporan Stok Obat</h2>
    <p>Laporan per Bulan: {{ $bulanTampil }}</p>

    <table>
        <thead>
            <tr>
                <th style="width: 10%;">Kode</th>
                <th style="width: 25%;">Nama Obat</th>
                <th style="width: 10%;">Satuan</th>
                <th style="width: 11%; text-align:right;">Total Masuk</th>
                <th style="width: 11%; text-align:right;">Total Keluar</th>
                <th style="width: 11%; text-align:right;">Sisa Stok</th>
                <th style="width: 11%;">Tgl Expire</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($stok as $s)
                <tr>
                    <td>{{ $s->item_code }}</td>
                    <td>{{ $s->nama_obat }}</td>
                    <td>{{ $s->unit_of_measurement }}</td>
                    <td style="text-align:right;">{{ $s->stok_masuk ?? 0 }}</td>
                    <td style="text-align:right;">{{ $s->stok_keluar ?? 0 }}</td>
                    <td style="text-align:right;">{{ $s->stok_akhir ?? 0 }}</td>
                    <td>
                        {{ $s->expire_date ? \Carbon\Carbon::parse($s->expire_date)->format('d M Y') : '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align:center;">Tidak ada data stok untuk periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>