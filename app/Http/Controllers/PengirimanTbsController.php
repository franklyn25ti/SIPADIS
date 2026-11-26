<?php

namespace App\Http\Controllers;

use App\Models\DetailPengiriman;
use App\Models\PasokanTbs;
use App\Models\PengirimanTbs;
use App\Models\PksPembeli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengirimanTbsController extends Controller
{
    public function index(Request $request)
    {
        $query = PengirimanTbs::with(['pks', 'detailPengiriman.pasokan.petani', 'hasilTimbangPks']);

        if ($request->filled('status_pengiriman')) {
            $query->where('status_pengiriman', $request->input('status_pengiriman'));
        }

        if ($request->filled('pks_id')) {
            $query->where('pks_id', $request->input('pks_id'));
        }

        $pengirimanList = $query->orderByDesc('tanggal_kirim')->orderByDesc('id')->get();
        $pksList = PksPembeli::orderBy('nama')->get();

        return view('distribusi.pengiriman.index', compact('pengirimanList', 'pksList'));
    }

    public function create()
    {
        $pksList = PksPembeli::orderBy('nama')->get();
        $noSuratJalan = PengirimanTbs::generateNoSuratJalan();

        // Ambil pasokan TBS beserta total berat yang sudah pernah dimuat
        $pasokanTersedia = PasokanTbs::with(['petani', 'detailPengiriman'])
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->get()
            ->map(function (PasokanTbs $pasokan) {
                $sudahDimuat = (float) $pasokan->detailPengiriman->sum('berat_dimuat');
                $pasokan->sisa_berat = round(max(0, (float) $pasokan->berat_netto - $sudahDimuat), 2);
                return $pasokan;
            })
            ->filter(fn (PasokanTbs $pasokan) => $pasokan->sisa_berat > 0)
            ->values();

        return view('distribusi.pengiriman.create', compact('pksList', 'noSuratJalan', 'pasokanTersedia'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_surat_jalan' => 'required|string|max:100|unique:pengiriman_tbs,no_surat_jalan',
            'tanggal_kirim'  => 'required|date',
            'kendaraan'      => 'required|string|max:100',
            'sopir'          => 'required|string|max:100',
            'pks_id'         => 'required|exists:pks_pembeli,id',
            'pasokan_ids'    => 'required|array|min:1',
            'pasokan_ids.*'  => 'required|exists:pasokan_tbs,id',
            'berat_dimuat'   => 'required|array',
        ], [
            'pasokan_ids.required' => 'Pilih minimal satu transaksi pasokan TBS petani untuk dimuat ke dalam truk.',
        ]);

        foreach ($validated['pasokan_ids'] as $pasokanId) {
            $berat = (float) ($request->input("berat_dimuat.{$pasokanId}") ?? 0);
            if ($berat <= 0) {
                return back()->withErrors([
                    'berat_dimuat' => 'Berat dimuat untuk setiap pasokan yang dipilih harus lebih besar dari 0 kg.',
                ])->withInput();
            }
        }

        $pengiriman = DB::transaction(function () use ($validated, $request) {
            $pengiriman = PengirimanTbs::create([
                'no_surat_jalan'    => $validated['no_surat_jalan'],
                'tanggal_kirim'     => $validated['tanggal_kirim'],
                'kendaraan'         => $validated['kendaraan'],
                'sopir'             => $validated['sopir'],
                'pks_id'            => $validated['pks_id'],
                'status_pengiriman' => 'dikirim',
            ]);

            foreach ($validated['pasokan_ids'] as $pasokanId) {
                $beratDimuat = round((float) $request->input("berat_dimuat.{$pasokanId}"), 2);
                DetailPengiriman::create([
                    'pengiriman_id' => $pengiriman->id,
                    'pasokan_id'    => $pasokanId,
                    'berat_dimuat'  => $beratDimuat,
                ]);
            }

            return $pengiriman;
        });

        return redirect()->route('pengiriman-tbs.index')
            ->with('success', 'Data pengiriman TBS ke PKS berhasil dicatat.');
    }

    public function show(PengirimanTbs $pengiriman_tb)
    {
        $pengiriman_tb->load(['pks', 'detailPengiriman.pasokan.petani', 'hasilTimbangPks']);

        return view('distribusi.pengiriman.show', ['pengiriman' => $pengiriman_tb]);
    }

    public function destroy(PengirimanTbs $pengiriman_tb)
    {
        $pengiriman_tb->delete();

        return redirect()->route('pengiriman-tbs.index')
            ->with('success', 'Data pengiriman TBS ke PKS berhasil dihapus.');
    }
}
