<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanController extends Controller
{
    // Laporan realtime stok (halaman web)
    public function laporanStok(Request $request)
    {
        $request->validate([
            'filter' => 'nullable|in:stok_terbanyak,stok_tersedikit,expire_date',
            'bulan' => 'nullable|date_format:Y-m',
        ]);

        $filter = $request->filter;
        $bulanFilter = $request->bulan ? Carbon::parse($request->bulan) : null;
        
        // Tambahkan baris ini untuk mendefinisikan variabel
        $tanggalSekarang = Carbon::now();

        // Kueri untuk mendapatkan stok awal
        $stokAwalSubquery = DB::table('obats')
            ->select('obats.item_code', DB::raw('(COALESCE(SUM(obat_masuk.qty_masuk), 0) - COALESCE(SUM(obat_keluar.qty_keluar), 0)) as stok_awal'))
            ->leftJoin('obat_masuk', function ($join) use ($bulanFilter, $tanggalSekarang) {
                $join->on('obats.item_code', '=', 'obat_masuk.item_code')
                     ->where('obat_masuk.tanggal_masuk', '<', $bulanFilter ? $bulanFilter->startOfMonth() : $tanggalSekarang->startOfMonth());
            })
            ->leftJoin('obat_keluar', function ($join) use ($bulanFilter, $tanggalSekarang) {
                $join->on('obats.item_code', '=', 'obat_keluar.item_code')
                     ->where('obat_keluar.tanggal_keluar', '<', $bulanFilter ? $bulanFilter->startOfMonth() : $tanggalSekarang->startOfMonth());
            })
            ->groupBy('obats.item_code');

        $stok = DB::table('obats')
            ->select('obats.item_code', 'obats.nama_obat', 'obats.unit_of_measurement', 'stok_awal.stok_awal')
            ->selectRaw('COALESCE(SUM(obat_masuk.qty_masuk),0) as stok_masuk')
            ->selectRaw('COALESCE(SUM(obat_keluar.qty_keluar),0) as stok_keluar')
            ->selectRaw('(COALESCE(stok_awal.stok_awal, 0) + COALESCE(SUM(obat_masuk.qty_masuk),0) - COALESCE(SUM(obat_keluar.qty_keluar),0)) as stok_akhir')
            ->selectRaw('MIN(obat_masuk.expire_date) as expire_date')
            ->leftJoinSub($stokAwalSubquery, 'stok_awal', function ($join) {
                $join->on('obats.item_code', '=', 'stok_awal.item_code');
            })
            ->leftJoin('obat_masuk', function ($join) use ($bulanFilter) {
                $join->on('obats.item_code', '=', 'obat_masuk.item_code');
                if ($bulanFilter) {
                    $join->whereYear('obat_masuk.tanggal_masuk', $bulanFilter->year)
                         ->whereMonth('obat_masuk.tanggal_masuk', $bulanFilter->month);
                }
            })
            ->leftJoin('obat_keluar', function ($join) use ($bulanFilter) {
                $join->on('obats.item_code', '=', 'obat_keluar.item_code');
                if ($bulanFilter) {
                    $join->whereYear('obat_keluar.tanggal_keluar', $bulanFilter->year)
                         ->whereMonth('obat_keluar.tanggal_keluar', $bulanFilter->month);
                }
            })
            ->groupBy('obats.item_code', 'obats.nama_obat', 'obats.unit_of_measurement', 'stok_awal.stok_awal');

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
        $bulanTampil = $bulanFilter ? $bulanFilter->translatedFormat('F Y') : 'Semua';

        return view('laporan.stok', compact('stok', 'filter', 'bulanTampil'));
    }

    // Cetak PDF laporan stok saat ini
    public function cetakStok(Request $request)
    {
        $request->validate([
            'filter' => 'nullable|in:stok_terbanyak,stok_tersedikit,expire_date',
            'bulan' => 'nullable|date_format:Y-m',
        ]);

        $filter = $request->filter;
        $bulanFilter = $request->bulan ? Carbon::parse($request->bulan) : null;
        $tanggalSekarang = Carbon::now();

        // Kueri untuk mendapatkan stok awal
        $stokAwalSubquery = DB::table('obats')
            ->select('obats.item_code', DB::raw('(COALESCE(SUM(obat_masuk.qty_masuk), 0) - COALESCE(SUM(obat_keluar.qty_keluar), 0)) as stok_awal'))
            ->leftJoin('obat_masuk', function ($join) use ($bulanFilter, $tanggalSekarang) {
                $join->on('obats.item_code', '=', 'obat_masuk.item_code')
                     ->where('obat_masuk.tanggal_masuk', '<', $bulanFilter ? $bulanFilter->startOfMonth() : $tanggalSekarang->startOfMonth());
            })
            ->leftJoin('obat_keluar', function ($join) use ($bulanFilter, $tanggalSekarang) {
                $join->on('obats.item_code', '=', 'obat_keluar.item_code')
                     ->where('obat_keluar.tanggal_keluar', '<', $bulanFilter ? $bulanFilter->startOfMonth() : $tanggalSekarang->startOfMonth());
            })
            ->groupBy('obats.item_code');

        $stok = DB::table('obats')
            ->select('obats.item_code', 'obats.nama_obat', 'obats.unit_of_measurement', 'stok_awal.stok_awal')
            ->selectRaw('COALESCE(SUM(obat_masuk.qty_masuk),0) as stok_masuk')
            ->selectRaw('COALESCE(SUM(obat_keluar.qty_keluar),0) as stok_keluar')
            ->selectRaw('(COALESCE(stok_awal.stok_awal, 0) + COALESCE(SUM(obat_masuk.qty_masuk),0) - COALESCE(SUM(obat_keluar.qty_keluar),0)) as stok_akhir')
            ->selectRaw('MIN(obat_masuk.expire_date) as expire_date')
            ->leftJoinSub($stokAwalSubquery, 'stok_awal', function ($join) {
                $join->on('obats.item_code', '=', 'stok_awal.item_code');
            })
            ->leftJoin('obat_masuk', function ($join) use ($bulanFilter) {
                $join->on('obats.item_code', '=', 'obat_masuk.item_code');
                if ($bulanFilter) {
                    $join->whereYear('obat_masuk.tanggal_masuk', $bulanFilter->year)
                         ->whereMonth('obat_masuk.tanggal_masuk', $bulanFilter->month);
                }
            })
            ->leftJoin('obat_keluar', function ($join) use ($bulanFilter) {
                $join->on('obats.item_code', '=', 'obat_keluar.item_code');
                if ($bulanFilter) {
                    $join->whereYear('obat_keluar.tanggal_keluar', $bulanFilter->year)
                         ->whereMonth('obat_keluar.tanggal_keluar', $bulanFilter->month);
                }
            })
            ->groupBy('obats.item_code', 'obats.nama_obat', 'obats.unit_of_measurement', 'stok_awal.stok_awal');

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
        $bulanTampil = $bulanFilter ? $bulanFilter->translatedFormat('F Y') : 'Semua';

        $pdf = Pdf::loadView('laporan.stok_pdf', compact('stok', 'filter', 'bulanTampil'))
            ->setPaper('a4', orientation: 'portrait');

        return $pdf->download('laporan-stok-' . date('Y-m-d') . '.pdf');
    }
}