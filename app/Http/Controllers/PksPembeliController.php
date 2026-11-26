<?php

namespace App\Http\Controllers;

use App\Models\PksPembeli;
use Illuminate\Http\Request;

class PksPembeliController extends Controller
{
    public function index(Request $request)
    {
        $query = PksPembeli::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%")
                    ->orWhere('no_kontak', 'like', "%{$search}%");
            });
        }

        $pksList = $query->latest()->get();

        return view('pks.index', compact('pksList'));
    }

    public function create()
    {
        return view('pks.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'       => 'required|string|max:255',
            'alamat'     => 'nullable|string',
            'no_kontak'  => 'nullable|string|max:30',
            'keterangan' => 'nullable|string',
        ]);

        PksPembeli::create($validated);

        return redirect()->route('pks-pembeli.index')->with('success', 'Data PKS / Pembeli berhasil ditambahkan.');
    }

    public function edit(PksPembeli $pks_pembeli)
    {
        return view('pks.edit', ['pks' => $pks_pembeli]);
    }

    public function update(Request $request, PksPembeli $pks_pembeli)
    {
        $validated = $request->validate([
            'nama'       => 'required|string|max:255',
            'alamat'     => 'nullable|string',
            'no_kontak'  => 'nullable|string|max:30',
            'keterangan' => 'nullable|string',
        ]);

        $pks_pembeli->update($validated);

        return redirect()->route('pks-pembeli.index')->with('success', 'Data PKS / Pembeli berhasil diperbarui.');
    }

    public function destroy(PksPembeli $pks_pembeli)
    {
        $pks_pembeli->delete();

        return redirect()->route('pks-pembeli.index')->with('success', 'Data PKS / Pembeli berhasil dihapus.');
    }
}
