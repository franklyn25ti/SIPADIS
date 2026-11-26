<?php

namespace App\Http\Controllers;

use App\Models\HasilTimbangPks;
use App\Models\PengirimanTbs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HasilTimbangPksController extends Controller
{
    public function index()
    {
        $hasilTimbangList = HasilTimbangPks::with(['pengiriman.pks', 'pengiriman.detailPengiriman.pasokan.petani'])
            ->latest()
            ->get();

        $pengirimanDikirim = PengirimanTbs::with(['pks', 'detailPengiriman'])
            ->where('status_pengiriman', 'dikirim')
            ->orderByDesc('tanggal_kirim')
            ->get();

        return view('distribusi.timbang-pks.index', compact('hasilTimbangList', 'pengirimanDikirim'));
    }

    public function create(Request $request)
    {
        $pengirimanList = PengirimanTbs::with(['pks', 'detailPengiriman.pasokan.petani'])
            ->where('status_pengiriman', 'dikirim')
            ->orderByDesc('tanggal_kirim')
            ->get();

        $selectedPengirimanId = $request->input('pengiriman_id');
        $suggestedNoNota = 'NTP-' . now()->format('Ymd') . '-' . str_pad((string) (HasilTimbangPks::count() + 1), 3, '0', STR_PAD_LEFT);

        return view('distribusi.timbang-pks.create', compact('pengirimanList', 'selectedPengirimanId', 'suggestedNoNota'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_nota_timbang' => 'required|string|max:100|unique:hasil_timbang_pks,no_nota_timbang',
            'pengiriman_id'   => 'required|exists:pengiriman_tbs,id|unique:hasil_timbang_pks,pengiriman_id',
            'bruto'           => 'required|numeric|gt:0',
            'tara'            => 'required|numeric|min:0|lt:bruto',
            'rafaksi'         => 'nullable|numeric|min:0',
            'harga_jual'      => 'required|numeric|min:0',
        ]);

        $bruto = round((float) $validated['bruto'], 2);
        $tara = round((float) $validated['tara'], 2);
        $netto = round($bruto - $tara, 2);
        $rafaksi = round((float) ($validated['rafaksi'] ?? 0), 2);

        if ($rafaksi > $netto) {
            return back()->withErrors([
                'rafaksi' => 'Potongan rafaksi tidak boleh melebihi berat netto.',
            ])->withInput();
        }

        $beratDibayar = round($netto - $rafaksi, 2);
        $hargaJual = round((float) $validated['harga_jual'], 2);
        $nilaiPenjualan = round($beratDibayar * $hargaJual, 2);

        DB::transaction(function () use ($validated, $bruto, $tara, $netto, $rafaksi, $beratDibayar, $hargaJual, $nilaiPenjualan) {
            HasilTimbangPks::create([
                'no_nota_timbang'   => $validated['no_nota_timbang'],
                'pengiriman_id'     => $validated['pengiriman_id'],
                'bruto'             => $bruto,
                'tara'              => $tara,
                'netto'             => $netto,
                'rafaksi'           => $rafaksi,
                'berat_dibayar'     => $beratDibayar,
                'harga_jual'        => $hargaJual,
                'nilai_penjualan'   => $nilaiPenjualan,
                'status_penerimaan' => 'belum_diterima',
                'jumlah_diterima'   => 0,
            ]);

            PengirimanTbs::where('id', $validated['pengiriman_id'])
                ->update(['status_pengiriman' => 'selesai_timbang']);
        });

        return redirect()->route('hasil-timbang-pks.index')
            ->with('success', 'Nota hasil timbang PKS berhasil disimpan dan status pengiriman diperbarui menjadi Selesai Timbang.');
    }

    public function edit(HasilTimbangPks $hasil_timbang_pk)
    {
        $hasil_timbang_pk->load(['pengiriman.pks', 'pengiriman.detailPengiriman.pasokan.petani']);

        return view('distribusi.timbang-pks.edit', ['hasilTimbang' => $hasil_timbang_pk]);
    }

    public function update(Request $request, HasilTimbangPks $hasil_timbang_pk)
    {
        $validated = $request->validate([
            'no_nota_timbang' => 'required|string|max:100|unique:hasil_timbang_pks,no_nota_timbang,' . $hasil_timbang_pk->id,
            'bruto'           => 'required|numeric|gt:0',
            'tara'            => 'required|numeric|min:0|lt:bruto',
            'rafaksi'         => 'nullable|numeric|min:0',
            'harga_jual'      => 'required|numeric|min:0',
        ]);

        $bruto = round((float) $validated['bruto'], 2);
        $tara = round((float) $validated['tara'], 2);
        $netto = round($bruto - $tara, 2);
        $rafaksi = round((float) ($validated['rafaksi'] ?? 0), 2);

        if ($rafaksi > $netto) {
            return back()->withErrors([
                'rafaksi' => 'Potongan rafaksi tidak boleh melebihi berat netto.',
            ])->withInput();
        }

        $beratDibayar = round($netto - $rafaksi, 2);
        $hargaJual = round((float) $validated['harga_jual'], 2);
        $nilaiPenjualan = round($beratDibayar * $hargaJual, 2);

        $hasil_timbang_pk->update([
            'no_nota_timbang' => $validated['no_nota_timbang'],
            'bruto'           => $bruto,
            'tara'            => $tara,
            'netto'           => $netto,
            'rafaksi'         => $rafaksi,
            'berat_dibayar'   => $beratDibayar,
            'harga_jual'      => $hargaJual,
            'nilai_penjualan' => $nilaiPenjualan,
        ]);

        return redirect()->route('hasil-timbang-pks.index')
            ->with('success', 'Data nota hasil timbang PKS berhasil diperbarui.');
    }

    public function destroy(HasilTimbangPks $hasil_timbang_pk)
    {
        DB::transaction(function () use ($hasil_timbang_pk) {
            $pengirimanId = $hasil_timbang_pk->pengiriman_id;
            $hasil_timbang_pk->delete();

            PengirimanTbs::where('id', $pengirimanId)
                ->update(['status_pengiriman' => 'dikirim']);
        });

        return redirect()->route('hasil-timbang-pks.index')
            ->with('success', 'Data nota hasil timbang PKS berhasil dihapus dan status pengiriman dikembalikan ke Dikirim.');
    }
}
