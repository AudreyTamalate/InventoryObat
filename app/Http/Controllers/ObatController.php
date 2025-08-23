<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    // Tampilkan semua obat
    public function index()
    {
        $obats = Obat::all();
        return view('obat.index', compact('obats'));
    }

    // Form tambah obat
    public function create()
    {
        return view('obat.create');
    }

    // Simpan obat baru
    public function store(Request $request)
    {
        $request->validate([
            'item_code' => 'required|unique:obats,item_code|max:20',
            'nama_obat' => 'required|unique:obats,nama_obat|max:100',
            'unit_of_measurement' => 'nullable|string|max:20',
            'produsen' => 'required|string|max:50',
        ]);

        Obat::create($request->only([
            'item_code',
            'nama_obat',
            'unit_of_measurement',
            'produsen',
        ]));

        return redirect()->route('obat.index')->with('success', 'Obat berhasil ditambahkan');
    }

    // Form edit obat
    public function edit(Obat $obat)
    {
        return view('obat.edit', compact('obat'));
    }

    // Simpan perubahan obat
    public function update(Request $request, Obat $obat)
    {
        $request->validate([
            'item_code' => 'required|max:20|unique:obats,item_code,' . $obat->id,
            'nama_obat' => 'required|max:100|unique:obats,nama_obat,' . $obat->id,
            'unit_of_measurement' => 'nullable|string|max:20',
            'produsen' => 'required|string|max:50',
        ]);

        $obat->update($request->only([
            'item_code',
            'nama_obat',
            'unit_of_measurement',
            'produsen',
        ]));

        return redirect()->route('obat.index')->with('success', 'Obat berhasil diperbarui');
    }

    // Hapus obat
    public function destroy(Obat $obat)
    {
        $obat->delete();
        return redirect()->route('obat.index')->with('success', 'Obat berhasil dihapus');
    }

}
