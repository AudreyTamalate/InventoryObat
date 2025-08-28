<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StokExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $filter;

    public function __construct($filter = null)
    {
        $this->filter = $filter;
    }

    public function collection()
    {
        $stok = DB::table('obats')
            ->select('obats.item_code', 'obats.nama_obat', 'obats.unit_of_measurement')
            ->selectRaw('COALESCE(SUM(obat_masuk.qty_masuk),0) as stok_masuk')
            ->selectRaw('COALESCE(SUM(obat_keluar.qty_keluar),0) as stok_keluar')
            ->selectRaw('(COALESCE(SUM(obat_masuk.qty_masuk),0) - COALESCE(SUM(obat_keluar.qty_keluar),0)) as stok_akhir')
            ->selectRaw('MIN(obat_masuk.expire_date) as expire_date')
            ->leftJoin('obat_masuk', 'obats.item_code', '=', 'obat_masuk.item_code')
            ->leftJoin('obat_keluar', 'obats.item_code', '=', 'obat_keluar.item_code')
            ->groupBy('obats.item_code', 'obats.nama_obat', 'obats.unit_of_measurement');

        if ($this->filter === 'stok_terbanyak') {
            $stok->orderByDesc('stok_akhir');
        } elseif ($this->filter === 'stok_tersedikit') {
            $stok->orderBy('stok_akhir');
        } elseif ($this->filter === 'expire_date') {
            $stok->orderBy('expire_date');
        } else {
            $stok->orderBy('obats.nama_obat');
        }

        return $stok->get();
    }

    public function headings(): array
    {
        return [
            'Kode Obat',
            'Nama Obat',
            'Satuan',
            'Jumlah Masuk',
            'Jumlah Keluar',
            'Stok Akhir',
            'Expire Date',
        ];
    }

    public function map($row): array
    {
        return [
            $row->item_code,
            $row->nama_obat,
            $row->unit_of_measurement,
            $row->stok_masuk,
            $row->stok_keluar,
            $row->stok_akhir,
            $row->expire_date,
        ];
    }
}
