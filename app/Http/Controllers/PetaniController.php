<?php

namespace App\Models;

namespace App\Http\Controllers;

use App\Models\Petani;
use Illuminate\Http\Request;

class PetaniController extends Controller
{
    public function index()
    {
        $petanis = Petani::latest()->get();
        return view('petani.index', compact('petanis'));
    }

    public function create()
    {
        return view('petani.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_petani' => 'required|string|max:255',
            'no_hp'       => 'nullable|string|max:20',
            'alamat'      => 'nullable|string',
            'luas_lahan'  => 'required|numeric|min:0',
        ]);

        Petani::create($request->all());
        return redirect()->route('petani.index')->with('success', 'Data petani berhasil ditambahkan.');
    }

    public function edit(Petani $petani)
    {
        return view('petani.edit', compact('petani'));
    }

    public function update(Request $request, Petani $petani)
    {
        $request->validate([
            'nama_petani' => 'required|string|max:255',
            'no_hp'       => 'nullable|string|max:20',
            'alamat'      => 'nullable|string',
            'luas_lahan'  => 'required|numeric|min:0',
        ]);

        $petani->update($request->all());
        return redirect()->route('petani.index')->with('success', 'Data petani berhasil diperbarui.');
    }

    public function destroy(Petani $petani)
    {
        $petani->delete();
        return redirect()->route('petani.index')->with('success', 'Data petani berhasil dihapus.');
    }
}