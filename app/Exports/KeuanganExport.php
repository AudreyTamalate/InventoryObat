<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KeuanganExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    protected $startDate;
    protected $endDate;
    protected $bulanTampil;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
        $this->bulanTampil = Carbon::parse($startDate)->translatedFormat('F Y');
    }

    public function collection()
    {
        $laporan = DB::table('obat_keluar')
            ->join('obats', 'obat_keluar.item_code', '=', 'obats.item_code')
            ->whereBetween('obat_keluar.tanggal_keluar', [$this->startDate, $this->endDate])
            ->select(
                'obats.item_code',
                'obats.nama_obat',
                DB::raw('(SELECT harga_beli FROM obat_masuk WHERE item_code = obats.item_code ORDER BY tanggal_masuk DESC LIMIT 1) as harga_beli'),
                DB::raw('SUM(obat_keluar.qty_keluar) as jumlah_keluar'),
                DB::raw('obat_keluar.harga_jual as harga_jual'),
                DB::raw('SUM(obat_keluar.harga_jual * obat_keluar.qty_keluar) as total_jual')
            )
            ->groupBy('obats.item_code', 'obats.nama_obat', 'obat_keluar.harga_jual')
            ->get();

        return $laporan->map(function ($item) {
            $item->total_beli = $item->harga_beli * $item->jumlah_keluar;
            $item->pendapatan = $item->total_jual - $item->total_beli;
            return [
                'Kode Obat'     => $item->item_code,
                'Nama Obat'     => $item->nama_obat,
                'Harga Beli'    => 'Rp ' . number_format($item->harga_beli, 0, ',', '.'),
                'Jumlah Keluar' => $item->jumlah_keluar,
                'Harga Jual'    => 'Rp ' . number_format($item->harga_jual, 0, ',', '.'),
                'Total Beli'    => 'Rp ' . number_format($item->total_beli, 0, ',', '.'),
                'Total Jual'    => 'Rp ' . number_format($item->total_jual, 0, ',', '.'),
                'Pendapatan'    => 'Rp ' . number_format($item->pendapatan, 0, ',', '.'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            ['Laporan Keuangan'],
            ["Periode: {$this->bulanTampil}"],
            [],
            ['Kode Obat', 'Nama Obat', 'Harga Beli', 'Jumlah Keluar', 'Harga Jual', 'Total Beli', 'Total Jual', 'Pendapatan'],
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
                $lastRow = $sheet->getHighestRow();

                $totals = DB::table('obat_keluar')
                    ->join('obats', 'obat_keluar.item_code', '=', 'obats.item_code')
                    ->join('obat_masuk', 'obats.item_code', '=', 'obat_masuk.item_code')
                    ->whereBetween('obat_keluar.tanggal_keluar', [$this->startDate, $this->endDate])
                    ->select(
                        DB::raw('SUM(obat_keluar.qty_keluar * obat_masuk.harga_beli) as total_beli'),
                        DB::raw('SUM(obat_keluar.qty_keluar * obat_keluar.harga_jual) as total_jual')
                    )
                    ->first();
                
                $currentRow = $lastRow + 1;
                $sheet->setCellValue('A' . $currentRow, 'TOTAL KESELURUHAN');
                $sheet->setCellValue('F' . $currentRow, 'Rp ' . number_format($totals->total_beli, 0, ',', '.'));
                $sheet->setCellValue('G' . $currentRow, 'Rp ' . number_format($totals->total_jual, 0, ',', '.'));
                $sheet->setCellValue('H' . $currentRow, 'Rp ' . number_format($totals->total_jual - $totals->total_beli, 0, ',', '.'));

                $sheet->mergeCells('A1:H1');
                $sheet->mergeCells('A2:H2');

                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
                $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');

                $sheet->getStyle('A4:H4')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => [
                        'fillType' => 'solid',
                        'startColor' => ['rgb' => '4CAF50'],
                    ],
                ]);

                $sheet->getStyle('A' . $currentRow . ':H' . $currentRow)->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => 'solid',
                        'startColor' => ['rgb' => 'F2F2F2'],
                    ],
                ]);

                // Hapus baris ini untuk membuat teks rata kiri
                // $sheet->getStyle('A' . $currentRow)->getAlignment()->setHorizontal('center');
                
                $sheet->mergeCells('A' . $currentRow . ':E' . $currentRow);

                foreach (range('A', 'H') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            }
        ];
    }
}