<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('obat_keluar', function (Blueprint $table) {
            $table->id();
            $table->string('item_code', 20); // foreign key ke obats
            $table->integer('harga_jual');
            $table->integer('qty_keluar');
            $table->date('tanggal_keluar');
            $table->string('keterangan')->nullable(); // contoh: untuk pasien, retur, dll
            $table->timestamps();

            // relasi ke obats
            $table->foreign('item_code')
                  ->references('item_code')
                  ->on('obats')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('obat_keluar');
    }
};
