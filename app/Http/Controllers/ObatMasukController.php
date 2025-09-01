<?php

namespace App\Http\Controllers;

use App\Models\ObatMasuk;
use App\Models\Obat;
use Illuminate\Http\Request;

class ObatMasukController extends Controller
{
    // Tampilkan semua obat masuk
    public function index()
    {
        $obatMasuk = ObatMasuk::with('obat')->paginate(10); // include relasi obat
        return view('obat-masuk.index', compact('obatMasuk'));
    }

    // Form tambah obat masuk
    public function create()
    {
        $obats = Obat::all(); // untuk dropdown pilih item_code
        return view('obat-masuk.create', compact('obats'));
    }

    // Simpan obat masuk baru
    public function store(Request $request)
    {
        $request->validate([
            'item_code' => 'required|exists:obats,item_code',
            'farmasi' => 'required|string|max:50',
            'batch' => 'required|string|max:50',
            'harga_beli' => 'required|integer',
            'qty_masuk' => 'required|integer',
            'tanggal_masuk' => 'required|date',
            'expire_date' => 'required|date',
        ]);

        ObatMasuk::create($request->only([
            'item_code',
            'farmasi',
            'batch',
            'harga_beli',
            'qty_masuk',
            'tanggal_masuk',
            'expire_date'
        ]));

        return redirect()->route('obat-masuk.index')->with('success', 'Data obat masuk berhasil ditambahkan');
    }

    // Form edit obat masuk
    public function edit(ObatMasuk $obatMasuk)
    {
        $obats = Obat::all();
        return view('obat-masuk.edit', compact('obatMasuk', 'obats'));
    }

    // Update data obat masuk
    public function update(Request $request, ObatMasuk $obatMasuk)
    {
        $request->validate([
            'item_code' => 'required|exists:obats,item_code',
            'farmasi' => 'required|string|max:50',
            'batch' => 'required|string|max:50',
            'harga_beli' => 'required|integer',
            'qty_masuk' => 'required|integer',
            'tanggal_masuk' => 'required|date',
            'expire_date' => 'required|date',
        ]);

        $obatMasuk->update($request->only([
            'item_code',
            'farmasi',
            'batch',
            'harga_beli',
            'qty_masuk',
            'tanggal_masuk',
            'expire_date'
        ]));

        return redirect()->route('obat-masuk.index')->with('success', 'Data obat masuk berhasil diperbarui');
    }

    // Hapus data obat masuk
    public function destroy(ObatMasuk $obatMasuk)
    {
        $obatMasuk->delete();
        return redirect()->route('obat-masuk.index')->with('success', 'Data obat masuk berhasil dihapus');
    }
}
