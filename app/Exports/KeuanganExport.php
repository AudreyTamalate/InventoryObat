<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromView;

class KeuanganExport implements FromView
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function view(): View
    {
        $laporan = DB::table('obat_keluar')
            ->join('obats', 'obat_keluar.item_code', '=', 'obats.item_code')
            ->whereBetween('obat_keluar.tanggal_keluar', [$this->startDate, $this->endDate])
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

        // Hitung pendapatan dan total keseluruhan
        $totalBeli = 0;
        $totalJual = 0;
        $totalPendapatan = 0;

        foreach ($laporan as $item) {
            $item->pendapatan = $item->total_jual - $item->total_beli;

            $totalBeli += $item->total_beli;
            $totalJual += $item->total_jual;
            $totalPendapatan += $item->pendapatan;
        }

        return view('exports.keuangan', [
            'laporan' => $laporan,
            'totalBeli' => $totalBeli,
            'totalJual' => $totalJual,
            'totalPendapatan' => $totalPendapatan,
            'bulan' => $this->startDate->translatedFormat('F Y')
        ]);
    }
}
