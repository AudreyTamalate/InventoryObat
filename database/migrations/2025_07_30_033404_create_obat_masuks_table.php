<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('obat_masuk', function (Blueprint $table) {
            $table->id();
            $table->string('item_code', 20); // foreign key ke obats
            $table->string('farmasi', 50);  
            $table->string('batch', 50);
            $table->integer('harga_beli');
            $table->integer('qty_masuk');
            $table->date('tanggal_masuk');
            $table->date('expire_date');
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
        Schema::dropIfExists('obat_masuk');
    }
};
