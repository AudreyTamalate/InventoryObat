<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        .total-row {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Keuangan Apotek XYZ</h2>
        <p>Periode: {{ $bulanTampil }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Item Code</th>
                <th>Nama Obat</th>
                <th>Harga Beli</th>
                <th>Harga Jual</th>
                <th>Jumlah Keluar</th>
                <th>Total Beli</th>
                <th>Total Jual</th>
                <th>Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $grandTotalBeli = 0;
                $grandTotalJual = 0;
                $grandTotalPendapatan = 0;
            @endphp
            @foreach($laporan as $item)
            <tr>
                <td>{{ $item->item_code }}</td>
                <td>{{ $item->nama_obat }}</td>
                <td style="text-align: right;">Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                <td style="text-align: right;">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                <td style="text-align: center;">{{ $item->jumlah_keluar }}</td>
                <td style="text-align: right;">Rp {{ number_format($item->total_beli, 0, ',', '.') }}</td>
                <td style="text-align: right;">Rp {{ number_format($item->total_jual, 0, ',', '.') }}</td>
                <td style="text-align: right;">Rp {{ number_format($item->pendapatan, 0, ',', '.') }}</td>
            </tr>
            @php
                $grandTotalBeli += $item->total_beli;
                $grandTotalJual += $item->total_jual;
                $grandTotalPendapatan += $item->pendapatan;
            @endphp
            @endforeach
            <tr class="total-row">
                <td colspan="5" style="text-align: right;">TOTAL:</td>
                <td style="text-align: right;">Rp {{ number_format($grandTotalBeli, 0, ',', '.') }}</td>
                <td style="text-align: right;">Rp {{ number_format($grandTotalJual, 0, ',', '.') }}</td>
                <td style="text-align: right;">Rp {{ number_format($grandTotalPendapatan, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
