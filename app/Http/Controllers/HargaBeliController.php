<?php

namespace App\Http\Controllers;

use App\Models\HargaBeli;
use Illuminate\Http\Request;

class HargaBeliController extends Controller
{
    public function index()
    {
        $hargaList = HargaBeli::orderByDesc('tanggal_berlaku')
            ->orderByDesc('id')
            ->get();

        $hargaAktif = HargaBeli::getHargaAktif();

        return view('harga-beli.index', compact('hargaList', 'hargaAktif'));
    }

    public function create()
    {
        return view('harga-beli.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_berlaku' => 'required|date',
            'harga_tbs'       => 'required|numeric|min:0',
            'satuan'          => 'nullable|string|max:20',
            'keterangan'      => 'nullable|string',
        ]);

        $validated['satuan'] = $validated['satuan'] ?? 'kg';

        HargaBeli::create($validated);

        return redirect()->route('harga-beli.index')->with('success', 'Harga beli TBS harian berhasil ditetapkan.');
    }

    public function edit(HargaBeli $harga_beli)
    {
        return view('harga-beli.edit', ['hargaBeli' => $harga_beli]);
    }

    public function update(Request $request, HargaBeli $harga_beli)
    {
        $validated = $request->validate([
            'tanggal_berlaku' => 'required|date',
            'harga_tbs'       => 'required|numeric|min:0',
            'satuan'          => 'nullable|string|max:20',
            'keterangan'      => 'nullable|string',
        ]);

        $validated['satuan'] = $validated['satuan'] ?? 'kg';

        $harga_beli->update($validated);

        return redirect()->route('harga-beli.index')->with('success', 'Data harga beli TBS berhasil diperbarui.');
    }

    public function destroy(HargaBeli $harga_beli)
    {
        $harga_beli->delete();

        return redirect()->route('harga-beli.index')->with('success', 'Data harga beli TBS berhasil dihapus.');
    }
}
