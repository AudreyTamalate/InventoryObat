<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class StokExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents
{
    protected $filter;
    protected $tanggal;

    public function __construct($filter = null)
    {
        $this->filter = $filter;
        $this->tanggal = Carbon::now()->translatedFormat('d F Y');
    }

    public function collection()
    {
        $stok = DB::table('obats')
            ->select(
                'obats.item_code',
                'obats.nama_obat',
                'obats.unit_of_measurement',
                DB::raw('COALESCE(SUM(obat_masuk.qty_masuk), 0) as stok_masuk'),
                DB::raw('COALESCE(SUM(obat_keluar.qty_keluar), 0) as stok_keluar'),
                DB::raw('(COALESCE(SUM(obat_masuk.qty_masuk), 0) - COALESCE(SUM(obat_keluar.qty_keluar), 0)) as stok_akhir'),
                DB::raw('MIN(obat_masuk.expire_date) as expire_date')
            )
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
            ['Laporan Stok Obat'],
            ["Tanggal: {$this->tanggal}"],
            [],
            ['Kode Obat', 'Nama Obat', 'Satuan', 'Jumlah Masuk', 'Jumlah Keluar', 'Stok Akhir', 'Expire Date'],
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

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            2 => ['font' => ['italic' => true]],
            4 => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Merge cells for title and subtitle
                $sheet->mergeCells('A1:G1');
                $sheet->mergeCells('A2:G2');

                // Center align title and subtitle
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
                $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');

                // Styling for table header
                $sheet->getStyle('A4:G4')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '10B981'], // Green header color
                    ],
                ]);

                // Auto-size columns
                foreach (range('A', 'G') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            }
        ];
    }
}