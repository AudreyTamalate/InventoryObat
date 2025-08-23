<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    // Laporan realtime stok
    public function laporanStok(Request $request)
    {
        $bulan = $request->bulan; // ex: 2025-08

        $stok = DB::table('obats')
    ->select(
        'obats.item_code',
        'obats.nama_obat',
        'obats.unit_of_measurement'
    )
    ->selectRaw('
        COALESCE(SUM(obat_masuk.qty_masuk),0) as stok_masuk,
        COALESCE(SUM(obat_keluar.qty_keluar),0) as stok_keluar,
        COALESCE(SUM(obat_masuk.qty_masuk),0) - COALESCE(SUM(obat_keluar.qty_keluar),0) as stok_akhir
    ')
    ->leftJoin('obat_masuk', function ($join) use ($bulan) {
        $join->on('obats.item_code', '=', 'obat_masuk.item_code');
        if ($bulan) {
            $join->whereMonth('obat_masuk.tanggal_masuk', '=', date('m', strtotime($bulan)))
                ->whereYear('obat_masuk.tanggal_masuk', '=', date('Y', strtotime($bulan)));
        }
    })
    ->leftJoin('obat_keluar', function ($join) use ($bulan) {
        $join->on('obats.item_code', '=', 'obat_keluar.item_code');
        if ($bulan) {
            $join->whereMonth('obat_keluar.tanggal_keluar', '=', date('m', strtotime($bulan)))
                ->whereYear('obat_keluar.tanggal_keluar', '=', date('Y', strtotime($bulan)));
        }
    })
    ->groupBy('obats.item_code', 'obats.nama_obat', 'obats.unit_of_measurement')
    ->get();


        return view('laporan.stok', compact('stok', 'bulan'));
    }

    // Cetak PDF
    public function cetakStok(Request $request)
    {
        $bulan = $request->bulan;

        $stok = DB::table('obats')
            ->select('obats.item_code', 'obats.nama_obat', 'obats.unit_of_measurement')
            ->selectRaw('
                COALESCE(SUM(obat_masuk.qty_masuk),0) 
                - COALESCE(SUM(obat_keluar.qty_keluar),0) as stok_akhir
            ')
            ->leftJoin('obat_masuk', function ($join) use ($bulan) {
                $join->on('obats.item_code', '=', 'obat_masuk.item_code');
                if ($bulan) {
                    $join->whereMonth('obat_masuk.tanggal_masuk', '=', date('m', strtotime($bulan)))
                        ->whereYear('obat_masuk.tanggal_masuk', '=', date('Y', strtotime($bulan)));
                }
            })
            ->leftJoin('obat_keluar', function ($join) use ($bulan) {
                $join->on('obats.item_code', '=', 'obat_keluar.item_code');
                if ($bulan) {
                    $join->whereMonth('obat_keluar.tanggal_keluar', '=', date('m', strtotime($bulan)))
                        ->whereYear('obat_keluar.tanggal_keluar', '=', date('Y', strtotime($bulan)));
                }
            })
            ->groupBy('obats.item_code', 'obats.nama_obat', 'obats.unit_of_measurement')
            ->get();

        $pdf = Pdf::loadView('laporan.stok_pdf', compact('stok', 'bulan'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('laporan-stok-' . $bulan . '.pdf');
    }
}
