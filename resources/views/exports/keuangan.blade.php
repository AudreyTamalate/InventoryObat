<table>
    <thead>
        <tr>
            <th>Kode Obat</th>
            <th>Nama Obat</th>
            <th>Jumlah Keluar</th>
            <th>Harga Beli</th>
            <th>Total Beli</th>
            <th>Harga Jual</th>
            <th>Total Jual</th>
            <th>Pendapatan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($laporan as $item)
            <tr>
                <td>{{ $item->item_code }}</td>
                <td>{{ $item->nama_obat }}</td>
                <td>{{ $item->jumlah_keluar }}</td>
                <td>Rp. {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                <td>Rp. {{ number_format($item->total_beli, 0, ',', '.') }}</td>
                <td>Rp. {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                <td>Rp. {{ number_format($item->total_jual, 0, ',', '.') }}</td>
                <td>Rp. {{ number_format($item->pendapatan, 0, ',', '.') }}</td>
            </tr>
        @endforeach

        <!-- Baris total keseluruhan -->
        <tr>
            <td colspan="4"><strong>Total Keseluruhan</strong></td>
            <td><strong>Rp. {{ number_format($totalBeli, 0, ',', '.') }}</strong></td>
            <td></td>
            <td><strong>Rp. {{ number_format($totalJual, 0, ',', '.') }}</strong></td>
            <td><strong>Rp. {{ number_format($totalPendapatan, 0, ',', '.') }}</strong></td>
        </tr>
    </tbody>
</table>
