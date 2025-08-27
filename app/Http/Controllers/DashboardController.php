<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Obat;
use App\Models\ObatMasuk;
use App\Models\ObatKeluar;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // === Total Obat ===
        $totalObat = Obat::count();

        // === Total Pembelian Bulan Ini ===
        $totalPembelian = ObatMasuk::whereMonth('tanggal_masuk', date('m'))
            ->whereYear('tanggal_masuk', date('Y'))
            ->select(DB::raw('SUM(harga_beli * qty_masuk) as total'))
            ->value('total');

        // === Total Penjualan Bulan Ini ===
        $totalPenjualan = ObatKeluar::whereMonth('tanggal_keluar', date('m'))
            ->whereYear('tanggal_keluar', date('Y'))
            ->select(DB::raw('SUM(harga_jual * qty_keluar) as total'))
            ->value('total');

        // === Expired Data ===
        $today = Carbon::today();
        $sixMonths = $today->copy()->addMonths(6);
        $threeMonths = $today->copy()->addMonths(3);

        $expData = [
            'belum'   => ObatMasuk::where('expire_date', '>', $sixMonths)->count(),
            '6bulan'  => ObatMasuk::whereBetween('expire_date', [$threeMonths, $sixMonths])->count(),
            '3bulan'  => ObatMasuk::whereBetween('expire_date', [$today, $threeMonths])->count(),
            'expired' => ObatMasuk::where('expire_date', '<', $today)->count(),
        ];

        // === Data Bulanan ===
        $monthlyData = [];
        $months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

        foreach (range(1, 12) as $month) {
            $monthlyPembelian = ObatMasuk::whereMonth('tanggal_masuk', $month)
                ->whereYear('tanggal_masuk', date('Y'))
                ->select(DB::raw('SUM(harga_beli * qty_masuk) as total'))
                ->value('total');

            $monthlyPenjualan = ObatKeluar::whereMonth('tanggal_keluar', $month)
                ->whereYear('tanggal_keluar', date('Y'))
                ->select(DB::raw('SUM(harga_jual * qty_keluar) as total'))
                ->value('total');

            $monthlyData['labels'][] = $months[$month-1];
            $monthlyData['pembelian'][] = $monthlyPembelian ?? 0;
            $monthlyData['penjualan'][] = $monthlyPenjualan ?? 0;
        }

        // === Stock Tersisa Per Obat ===
        $stokObat = Obat::select('obats.nama_obat',
            DB::raw('COALESCE(SUM(obat_masuk.qty_masuk),0) - COALESCE(SUM(obat_keluar.qty_keluar),0) as stok'))
            ->leftJoin('obat_masuk', 'obats.item_code', '=', 'obat_masuk.item_code')
            ->leftJoin('obat_keluar', 'obats.item_code', '=', 'obat_keluar.item_code')
            ->groupBy('obats.nama_obat')
            ->get();

        // === Obat Paling Laris ===
        $salesData = ObatKeluar::select('obats.nama_obat', DB::raw('SUM(obat_keluar.qty_keluar) as total'))
            ->join('obats', 'obats.item_code', '=', 'obat_keluar.item_code')
            ->whereYear('obat_keluar.tanggal_keluar', date('Y'))
            ->groupBy('obats.nama_obat')
            ->orderByDesc('total')
            ->pluck('total', 'obats.nama_obat'); 
            // hasilnya: ['Paracetamol' => 120, 'Amoxicillin' => 90]

        return view('dashboard.index', compact(
            'totalObat',
            'totalPembelian',
            'totalPenjualan',
            'monthlyData',
            'expData',
            'stokObat',
            'salesData'
        ));
    }
}
