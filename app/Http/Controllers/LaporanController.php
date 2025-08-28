<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Exports\KeuanganExport;
use App\Exports\StokExport;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    // Laporan realtime stok (halaman web)
    public function laporanStok(Request $request)
    {
        $request->validate([
            'filter' => 'nullable|in:stok_terbanyak,stok_tersedikit,expire_date',
        ]);

        $filter = $request->filter;
        $tanggalSekarang = Carbon::now();

        $stok = DB::table('obats')
            ->select('obats.item_code', 'obats.nama_obat', 'obats.unit_of_measurement')
            ->selectRaw('COALESCE(SUM(obat_masuk.qty_masuk),0) as stok_masuk')
            ->selectRaw('COALESCE(SUM(obat_keluar.qty_keluar),0) as stok_keluar')
            ->selectRaw('(COALESCE(SUM(obat_masuk.qty_masuk),0) - COALESCE(SUM(obat_keluar.qty_keluar),0)) as stok_akhir')
            ->selectRaw('MIN(obat_masuk.expire_date) as expire_date')
            ->leftJoin('obat_masuk', 'obats.item_code', '=', 'obat_masuk.item_code')
            ->leftJoin('obat_keluar', 'obats.item_code', '=', 'obat_keluar.item_code')
            ->groupBy('obats.item_code', 'obats.nama_obat', 'obats.unit_of_measurement');

        // Tambahkan filter
        if ($filter === 'stok_terbanyak') {
            $stok->orderByDesc('stok_akhir');
        } elseif ($filter === 'stok_tersedikit') {
            $stok->orderBy('stok_akhir');
        } elseif ($filter === 'expire_date') {
            $stok->orderBy('expire_date');
        } else {
            $stok->orderBy('obats.nama_obat');
        }

        $stok = $stok->get();
        $bulanTampil = 'Semua';

        return view('laporan.stok', compact('stok', 'filter', 'bulanTampil'));
    }

    // Cetak PDF laporan stok saat ini
    public function cetakStok(Request $request)
    {
        $request->validate([
            'filter' => 'nullable|in:stok_terbanyak,stok_tersedikit,expire_date',
        ]);

        $filter = $request->filter;
        $tanggalSekarang = Carbon::now();
        $bulanTampil = 'Semua';

        $stok = DB::table('obats')
            ->select('obats.item_code', 'obats.nama_obat', 'obats.unit_of_measurement')
            ->selectRaw('COALESCE(SUM(obat_masuk.qty_masuk),0) as stok_masuk')
            ->selectRaw('COALESCE(SUM(obat_keluar.qty_keluar),0) as stok_keluar')
            ->selectRaw('(COALESCE(SUM(obat_masuk.qty_masuk),0) - COALESCE(SUM(obat_keluar.qty_keluar),0)) as stok_akhir')
            ->selectRaw('MIN(obat_masuk.expire_date) as expire_date')
            ->leftJoin('obat_masuk', 'obats.item_code', '=', 'obat_masuk.item_code')
            ->leftJoin('obat_keluar', 'obats.item_code', '=', 'obat_keluar.item_code')
            ->groupBy('obats.item_code', 'obats.nama_obat', 'obats.unit_of_measurement');

        if ($filter === 'stok_terbanyak') {
            $stok->orderByDesc('stok_akhir');
        } elseif ($filter === 'stok_tersedikit') {
            $stok->orderBy('stok_akhir');
        } elseif ($filter === 'expire_date') {
            $stok->orderBy('expire_date');
        } else {
            $stok->orderBy('obats.nama_obat');
        }

        $stok = $stok->get();

        $pdf = Pdf::loadView('laporan.stok_pdf', compact('stok', 'filter', 'bulanTampil'))
            ->setPaper('a4', orientation: 'portrait');

        return $pdf->download('laporan-stok-' . date('Y-m-d') . '.pdf');
    }

    public function stokExcel(Request $request)
    {
        $filter = $request->input('filter');

        return Excel::download(new StokExport($filter), 'laporan-stok-' . date('Y-m-d') . '.xlsx');
    }

    // Laporan Keuangan
    public function keuangan(Request $request)
    {
        $bulanTerpilih = $request->input('bulan', date('Y-m'));

        try {
            $carbonBulan = Carbon::parse($bulanTerpilih);
        } catch (\Exception $e) {
            $carbonBulan = Carbon::now();
        }

        $startDate = $carbonBulan->copy()->startOfMonth();
        $endDate = $carbonBulan->copy()->endOfMonth();
        $bulanTampil = $carbonBulan->translatedFormat('F Y');

        $laporan = DB::table('obat_keluar')
            ->join('obats', 'obat_keluar.item_code', '=', 'obats.item_code')
            ->whereBetween('obat_keluar.tanggal_keluar', [$startDate, $endDate])
            ->select(
                'obats.item_code',
                'obats.nama_obat',
                DB::raw('(SELECT harga_beli FROM obat_masuk WHERE item_code = obats.item_code ORDER BY tanggal_masuk DESC LIMIT 1) as harga_beli'),
                DB::raw('SUM(obat_keluar.qty_keluar) as jumlah_keluar'),
                DB::raw('(SELECT harga_beli FROM obat_masuk WHERE item_code = obats.item_code ORDER BY tanggal_masuk DESC LIMIT 1) * SUM(obat_keluar.qty_keluar) as total_beli'),
                DB::raw('obat_keluar.harga_jual as harga_jual'),
                DB::raw('SUM(obat_keluar.harga_jual * obat_keluar.qty_keluar) as total_jual')
            )
            ->groupBy('obats.item_code', 'obats.nama_obat', 'obat_keluar.harga_jual')
            ->get();

        foreach ($laporan as $item) {
            $item->pendapatan = $item->total_jual - $item->total_beli;
        }

        // ✅ Tambah daftar bulan supaya bisa dipakai di <select>
        $daftarBulan = collect(range(0, 11))->map(function ($i) {
            $bulan = Carbon::now()->subMonths($i);
            return [
                'value' => $bulan->format('Y-m'),
                'name' => $bulan->translatedFormat('F Y'),
            ];
        })->reverse()->values();

        return view('laporan.keuangan', compact('laporan', 'bulanTerpilih', 'bulanTampil', 'daftarBulan'));
    }


    // Cetak PDF laporan keuangan
    public function keuanganPdf(Request $request)
    {
        $bulanTerpilih = $request->input('bulan', date('Y-m'));

        try {
            $carbonBulan = Carbon::parse($bulanTerpilih);
        } catch (\Exception $e) {
            $carbonBulan = Carbon::now(); // fallback kalau format salah
        }

        $startDate = $carbonBulan->copy()->startOfMonth();
        $endDate = $carbonBulan->copy()->endOfMonth();
        $bulanTampil = $carbonBulan->translatedFormat('F Y');

        $laporan = DB::table('obat_keluar')
            ->join('obats', 'obat_keluar.item_code', '=', 'obats.item_code')
            ->whereBetween('obat_keluar.tanggal_keluar', [$startDate, $endDate])
            ->select(
                'obats.item_code',
                'obats.nama_obat',
                DB::raw('(SELECT harga_beli FROM obat_masuk WHERE item_code = obats.item_code ORDER BY tanggal_masuk DESC LIMIT 1) as harga_beli'),
                DB::raw('SUM(obat_keluar.qty_keluar) as jumlah_keluar'),
                DB::raw('(SELECT harga_beli FROM obat_masuk WHERE item_code = obats.item_code ORDER BY tanggal_masuk DESC LIMIT 1) * SUM(obat_keluar.qty_keluar) as total_beli'),
                DB::raw('obat_keluar.harga_jual as harga_jual'),
                DB::raw('SUM(obat_keluar.harga_jual * obat_keluar.qty_keluar) as total_jual')
            )
            ->groupBy('obats.item_code', 'obats.nama_obat', 'obat_keluar.harga_jual')
            ->get();

        foreach ($laporan as $item) {
            $item->pendapatan = $item->total_jual - $item->total_beli;
        }

        $pdf = Pdf::loadView('laporan.keuangan_pdf', compact('laporan', 'bulanTampil'));
        return $pdf->download('laporan-keuangan-' . date('Y-m-d') . '.pdf');
    }
    public function keuanganExcel(Request $request)
    {
        $bulanTerpilih = $request->input('bulan', date('Y-m'));

        try {
            $carbonBulan = Carbon::parse($bulanTerpilih);
        } catch (\Exception $e) {
            $carbonBulan = Carbon::now();
        }

        $startDate = $carbonBulan->copy()->startOfMonth();
        $endDate = $carbonBulan->copy()->endOfMonth();
        $bulanTampil = $carbonBulan->translatedFormat('F Y');

        return Excel::download(new KeuanganExport($startDate, $endDate), 'laporan-keuangan-' . date('Y-m-d') . '.xlsx');
    }
}


