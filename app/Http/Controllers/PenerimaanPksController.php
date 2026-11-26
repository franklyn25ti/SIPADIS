<?php

namespace App\Http\Controllers;

use App\Models\HasilTimbangPks;
use App\Models\PksPembeli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenerimaanPksController extends Controller
{
    public function index(Request $request)
    {
        $query = HasilTimbangPks::with(['pengiriman.pks']);

        if ($request->filled('pks_id')) {
            $pksId = $request->input('pks_id');
            $query->whereHas('pengiriman', function ($q) use ($pksId) {
                $q->where('pks_id', $pksId);
            });
        }

        if ($request->filled('status_penerimaan')) {
            $query->where('status_penerimaan', $request->input('status_penerimaan'));
        }

        $notaList = $query->orderByDesc('id')->get();
        $pksList = PksPembeli::orderBy('nama')->get();

        $totalNilaiPenjualan = $notaList->sum(fn (HasilTimbangPks $item) => (float) $item->nilai_penjualan);
        $totalDanaDiterima = $notaList->sum(fn (HasilTimbangPks $item) => (float) $item->jumlah_diterima);
        $totalSisaPiutang = $notaList->sum(fn (HasilTimbangPks $item) => $item->sisa_piutang);

        return view('keuangan.penerimaan-pks.index', compact(
            'notaList',
            'pksList',
            'totalNilaiPenjualan',
            'totalDanaDiterima',
            'totalSisaPiutang'
        ));
    }

    public function edit(HasilTimbangPks $hasil_timbang_pks)
    {
        $hasil_timbang_pks->load(['pengiriman.pks']);

        return view('keuangan.penerimaan-pks.edit', ['nota' => $hasil_timbang_pks]);
    }

    public function update(Request $request, HasilTimbangPks $hasil_timbang_pks)
    {
        $validated = $request->validate([
            'tanggal_terima_dana' => 'required|date',
            'jumlah_masuk'        => 'required|numeric|gt:0',
        ]);

        $statusBaru = DB::transaction(function () use ($validated, $hasil_timbang_pks) {
            $nota = HasilTimbangPks::lockForUpdate()->findOrFail($hasil_timbang_pks->id);

            $totalDiterima = round((float) $nota->jumlah_diterima + (float) $validated['jumlah_masuk'], 2);
            $status = $totalDiterima >= round((float) $nota->nilai_penjualan, 2)
                ? 'lunas'
                : 'diterima_sebagian';

            $nota->update([
                'jumlah_diterima'     => $totalDiterima,
                'tanggal_terima_dana' => $validated['tanggal_terima_dana'],
                'status_penerimaan'   => $status,
            ]);

            return $status;
        });

        $labelStatus = $statusBaru === 'lunas' ? 'Lunas' : 'Diterima Sebagian';

        return redirect()->route('penerimaan-pks.index')
            ->with('success', "Penerimaan dana PKS berhasil dicatat. Status piutang nota menjadi {$labelStatus}.");
    }

    public function reset(HasilTimbangPks $hasil_timbang_pks)
    {
        $hasil_timbang_pks->update([
            'jumlah_diterima'     => 0,
            'tanggal_terima_dana' => null,
            'status_penerimaan'   => 'belum_diterima',
        ]);

        return redirect()->route('penerimaan-pks.index')
            ->with('success', 'Data penerimaan dana nota PKS berhasil direset.');
    }
}
