<?php

namespace App\Http\Controllers;

use App\Models\ObatKeluar;
use App\Models\Obat;
use Illuminate\Http\Request;

class ObatKeluarController extends Controller
{
    // Tampilkan semua data obat keluar
    public function index()
    {
        $obatKeluars = ObatKeluar::with('obat')->get();
        return view('obat-keluar.index', compact('obatKeluars'));
    }

    // Form tambah obat keluar
    public function create()
    {
        $obats = Obat::all(); // untuk pilih item_code
        return view('obat-keluar.create', compact('obats'));
    }

    // Simpan data obat keluar
    public function store(Request $request)
    {
        $request->validate([
            'item_code' => 'required|exists:obats,item_code',
            'harga_jual' => 'required|integer',
            'qty_keluar' => 'required|integer|min:1',
            'tanggal_keluar' => 'required|date',
            'keterangan' => 'nullable|string|max:255',
        ]);

        ObatKeluar::create($request->all());

        return redirect()->route('obat-keluar.index')->with('success', 'Data obat keluar berhasil ditambahkan');
    }

    // Form edit
    public function edit(ObatKeluar $obatKeluar)
    {
        $obats = Obat::all();
        return view('obat-keluar.edit', compact('obatKeluar', 'obats'));
    }

    // Simpan perubahan
    public function update(Request $request, ObatKeluar $obatKeluar)
    {
        $request->validate([
            'item_code' => 'required|exists:obats,item_code',
            'harga_jual' => 'required|integer',
            'qty_keluar' => 'required|integer|min:1',
            'tanggal_keluar' => 'required|date',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $obatKeluar->update($request->all());

        return redirect()->route('obat-keluar.index')->with('success', 'Data obat keluar berhasil diperbarui');
    }

    // Hapus data
    public function destroy(ObatKeluar $obatKeluar)
    {
        $obatKeluar->delete();
        return redirect()->route('obat-keluar.index')->with('success', 'Data obat keluar berhasil dihapus');
    }

}
