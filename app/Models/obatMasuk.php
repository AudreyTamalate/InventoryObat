<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObatMasuk extends Model
{
    use HasFactory;

    protected $table = 'obat_masuk'; 
    protected $primaryKey = 'id';

    protected $fillable = [
        'item_code',
        'farmasi',
        'batch',
        'harga_beli',
        'qty_masuk',
        'tanggal_masuk',
        'expire_date',
    ];

    /**
     * Relasi ke tabel obat
     */
    public function obat()
    {
        return $this->belongsTo(Obat::class, 'item_code', 'item_code');
    }
}
