<?php

namespace App\Http\Controllers;

use App\Models\PasokanTbs;
use App\Models\PembayaranPetani;
use App\Models\Petani;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PembayaranPetaniController extends Controller
{
    public function index(Request $request)
    {
        $tagihanQuery = PasokanTbs::with(['petani'])
            ->withSum('pembayaranPetani', 'jumlah_bayar')
            ->whereIn('status_pembayaran', ['belum_dibayar', 'dibayar_sebagian']);

        if ($request->filled('petani_id')) {
            $tagihanQuery->where('petani_id', $request->input('petani_id'));
        }

        if ($request->filled('status_pembayaran')) {
            $tagihanQuery->where('status_pembayaran', $request->input('status_pembayaran'));
        }

        $tagihanList = $tagihanQuery->orderByDesc('tanggal')->orderByDesc('id')->get();

        $riwayatPembayaran = PembayaranPetani::with(['pasokan.petani'])
            ->orderByDesc('tanggal_bayar')
            ->orderByDesc('id')
            ->get();

        $petaniList = Petani::orderBy('nama_petani')->get();

        $totalTagihanBelumLunas = $tagihanList->sum(fn (PasokanTbs $item) => $item->sisa_tagihan);
        $jumlahTagihanAktif = $tagihanList->count();
        $totalSudahDibayar = (float) PembayaranPetani::sum('jumlah_bayar');

        return view('keuangan.pembayaran-petani.index', compact(
            'tagihanList',
            'riwayatPembayaran',
            'petaniList',
            'totalTagihanBelumLunas',
            'jumlahTagihanAktif',
            'totalSudahDibayar'
        ));
    }

    public function create(Request $request)
    {
        $pasokanBelumLunas = PasokanTbs::with(['petani'])
            ->withSum('pembayaranPetani', 'jumlah_bayar')
            ->whereIn('status_pembayaran', ['belum_dibayar', 'dibayar_sebagian'])
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->get();

        $selectedPasokanId = $request->input('pasokan_id');

        return view('keuangan.pembayaran-petani.create', compact('pasokanBelumLunas', 'selectedPasokanId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pasokan_id'        => 'required|exists:pasokan_tbs,id',
            'tanggal_bayar'     => 'required|date',
            'jumlah_bayar'      => 'required|numeric|gt:0',
            'metode_pembayaran' => 'required|string|max:50',
            'keterangan'        => 'nullable|string',
        ]);

        $statusBaru = DB::transaction(function () use ($validated) {
            $pasokan = PasokanTbs::lockForUpdate()->findOrFail($validated['pasokan_id']);

            if ($pasokan->status_pembayaran === 'lunas') {
                throw ValidationException::withMessages([
                    'pasokan_id' => 'Tagihan pasokan ini sudah berstatus Lunas.',
                ]);
            }

            PembayaranPetani::create([
                'pasokan_id'        => $pasokan->id,
                'tanggal_bayar'     => $validated['tanggal_bayar'],
                'jumlah_bayar'      => round((float) $validated['jumlah_bayar'], 2),
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'keterangan'        => $validated['keterangan'] ?? null,
            ]);

            return $pasokan->syncStatusPembayaran();
        });

        $labelStatus = $statusBaru === 'lunas' ? 'Lunas' : 'Dibayar Sebagian';

        return redirect()->route('pembayaran-petani.index')
            ->with('success', "Pembayaran petani berhasil dicatat. Status pasokan diperbarui menjadi {$labelStatus}.");
    }

    public function destroy(PembayaranPetani $pembayaran_petani)
    {
        DB::transaction(function () use ($pembayaran_petani) {
            $pasokan = $pembayaran_petani->pasokan;
            $pembayaran_petani->delete();

            if ($pasokan) {
                $pasokan->syncStatusPembayaran();
            }
        });

        return redirect()->route('pembayaran-petani.index')
            ->with('success', 'Data pembayaran petani berhasil dihapus dan status tagihan telah disinkronkan ulang.');
    }
}
