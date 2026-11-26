<?php

namespace App\Http\Controllers;

use App\Models\Petani;
use Illuminate\Http\Request;

class PetaniController extends Controller
{
    public function index(Request $request)
    {
        $query = Petani::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama_petani', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%")
                    ->orWhere('no_hp', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status_aktif') && in_array($request->input('status_aktif'), ['0', '1'], true)) {
            $query->where('status_aktif', (bool) $request->input('status_aktif'));
        }

        $petanis = $query->latest()->get();

        return view('petani.index', compact('petanis'));
    }

    public function create()
    {
        return view('petani.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_petani'  => 'required|string|max:255',
            'no_hp'        => 'nullable|string|max:20',
            'alamat'       => 'nullable|string',
            'luas_lahan'   => 'required|numeric|min:0',
            'status_aktif' => 'nullable|boolean',
        ]);

        $validated['status_aktif'] = $request->boolean('status_aktif', true);

        Petani::create($validated);

        return redirect()->route('petani.index')->with('success', 'Data petani berhasil ditambahkan.');
    }

    public function edit(Petani $petani)
    {
        return view('petani.edit', compact('petani'));
    }

    public function update(Request $request, Petani $petani)
    {
        $validated = $request->validate([
            'nama_petani'  => 'required|string|max:255',
            'no_hp'        => 'nullable|string|max:20',
            'alamat'       => 'nullable|string',
            'luas_lahan'   => 'required|numeric|min:0',
            'status_aktif' => 'nullable|boolean',
        ]);

        $validated['status_aktif'] = $request->has('status_aktif')
            ? $request->boolean('status_aktif')
            : $petani->status_aktif;

        $petani->update($validated);

        return redirect()->route('petani.index')->with('success', 'Data petani berhasil diperbarui.');
    }

    public function destroy(Petani $petani)
    {
        $petani->delete();

        return redirect()->route('petani.index')->with('success', 'Data petani berhasil dihapus.');
    }
}
