<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    use HasFactory;

    protected $table = 'obats';   // nama tabel
    protected $primaryKey = 'id';

    protected $fillable = [
        'item_code',
        'nama_obat',
        'unit_of_measurement',
        'produsen',
    ];

    /**
     * Relasi ke obat masuk
     */
    public function obatMasuk()
    {
        return $this->hasMany(ObatMasuk::class, 'item_code', 'item_code');
    }

    /**
     * Relasi ke obat keluar
     */
    public function obatKeluar()
    {
        return $this->hasMany(ObatKeluar::class, 'item_code', 'item_code');
    }
}
