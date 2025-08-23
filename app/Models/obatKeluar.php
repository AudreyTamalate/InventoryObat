<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObatKeluar extends Model
{
    use HasFactory;

    protected $table = 'obat_keluar'; 
    protected $primaryKey = 'id';

    protected $fillable = [
        'item_code',
        'qty_keluar',
        'tanggal_keluar',
        'keterangan', // opsional: misalnya untuk catatan (resep, rusak, dll)
    ];

    /**
     * Relasi ke tabel obat
     */
    public function obat()
    {
        return $this->belongsTo(Obat::class, 'item_code', 'item_code');
    }
}
