<?php

namespace App\Http\Controllers;

use App\Models\HargaBeli;
use App\Models\PasokanTbs;
use App\Models\Petani;
use Illuminate\Http\Request;

class PasokanTbsController extends Controller
{
    public function index(Request $request)
    {
        $query = PasokanTbs::with(['petani', 'user']);

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal', '>=', $request->input('tanggal_mulai'));
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal', '<=', $request->input('tanggal_selesai'));
        }

        if ($request->filled('petani_id')) {
            $query->where('petani_id', $request->input('petani_id'));
        }

        if ($request->filled('status_pembayaran')) {
            $query->where('status_pembayaran', $request->input('status_pembayaran'));
        }

        $pasokanList = $query->orderByDesc('tanggal')->orderByDesc('id')->get();
        $petaniList = Petani::orderBy('nama_petani')->get();

        return view('transaksi.pasokan.index', compact('pasokanList', 'petaniList'));
    }

    public function create()
    {
        $petaniAktif = Petani::where('status_aktif', true)->orderBy('nama_petani')->get();
        $hargaHariIni = HargaBeli::getNominalAktif();
        $noTransaksi = PasokanTbs::generateNoTransaksi();

        return view('transaksi.pasokan.create', compact('petaniAktif', 'hargaHariIni', 'noTransaksi'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal'       => 'required|date',
            'petani_id'     => 'required|exists:petanis,id',
            'berat_timbang' => 'required|numeric|gt:0',
            'potongan'      => 'nullable|numeric|min:0|lte:berat_timbang',
            'harga_beli'    => 'required|numeric|min:0',
        ]);

        $beratTimbang = (float) $validated['berat_timbang'];
        $potongan = (float) ($validated['potongan'] ?? 0);
        $beratNetto = round($beratTimbang - $potongan, 2);
        $hargaBeli = (float) $validated['harga_beli'];
        $nilaiPembelian = round($beratNetto * $hargaBeli, 2);

        PasokanTbs::create([
            'no_transaksi'      => PasokanTbs::generateNoTransaksi($validated['tanggal']),
            'tanggal'           => $validated['tanggal'],
            'petani_id'         => $validated['petani_id'],
            'berat_timbang'     => $beratTimbang,
            'potongan'          => $potongan,
            'berat_netto'       => $beratNetto,
            'harga_beli'        => $hargaBeli,
            'nilai_pembelian'   => $nilaiPembelian,
            'status_pembayaran' => 'belum_dibayar',
            'user_id'           => $request->user()?->id,
        ]);

        return redirect()->route('pasokan-tbs.index')->with('success', 'Transaksi pasokan TBS berhasil dicatat.');
    }

    public function edit(PasokanTbs $pasokan_tb)
    {
        $petaniAktif = Petani::where('status_aktif', true)
            ->orWhere('id', $pasokan_tb->petani_id)
            ->orderBy('nama_petani')
            ->get();

        return view('transaksi.pasokan.edit', [
            'pasokan'     => $pasokan_tb,
            'petaniAktif' => $petaniAktif,
        ]);
    }

    public function update(Request $request, PasokanTbs $pasokan_tb)
    {
        $validated = $request->validate([
            'tanggal'       => 'required|date',
            'petani_id'     => 'required|exists:petanis,id',
            'berat_timbang' => 'required|numeric|gt:0',
            'potongan'      => 'nullable|numeric|min:0|lte:berat_timbang',
            'harga_beli'    => 'required|numeric|min:0',
        ]);

        $beratTimbang = (float) $validated['berat_timbang'];
        $potongan = (float) ($validated['potongan'] ?? 0);
        $beratNetto = round($beratTimbang - $potongan, 2);
        $hargaBeli = (float) $validated['harga_beli'];
        $nilaiPembelian = round($beratNetto * $hargaBeli, 2);

        $pasokan_tb->update([
            'tanggal'         => $validated['tanggal'],
            'petani_id'       => $validated['petani_id'],
            'berat_timbang'   => $beratTimbang,
            'potongan'        => $potongan,
            'berat_netto'     => $beratNetto,
            'harga_beli'      => $hargaBeli,
            'nilai_pembelian' => $nilaiPembelian,
        ]);

        return redirect()->route('pasokan-tbs.index')->with('success', 'Data transaksi pasokan TBS berhasil diperbarui.');
    }

    public function destroy(PasokanTbs $pasokan_tb)
    {
        $pasokan_tb->delete();

        return redirect()->route('pasokan-tbs.index')->with('success', 'Data transaksi pasokan TBS berhasil dihapus.');
    }
}
