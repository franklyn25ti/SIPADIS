<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HargaBeliController;
use App\Http\Controllers\HasilTimbangPksController;
use App\Http\Controllers\PasokanTbsController;
use App\Http\Controllers\PengirimanTbsController;
use App\Http\Controllers\PetaniController;
use App\Http\Controllers\PksPembeliController;
use App\Models\HargaBeli;
use App\Models\HasilTimbangPks;
use App\Models\PasokanTbs;
use App\Models\PengirimanTbs;
use App\Models\Petani;
use App\Models\PksPembeli;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        $stats = [
            'total_nilai_pembelian' => PasokanTbs::sum('nilai_pembelian'),
            'total_tonase_pasokan'  => PasokanTbs::sum('berat_netto'),
            'total_nilai_penjualan' => HasilTimbangPks::sum('nilai_penjualan'),
            'jumlah_petani_aktif'   => Petani::where('status_aktif', true)->count(),
            'jumlah_pengiriman'     => PengirimanTbs::count(),
            'harga_aktif'           => HargaBeli::getHargaAktif(),
            'total_transaksi'       => PasokanTbs::count(),
            'count_lunas'           => PasokanTbs::where('status_pembayaran', 'lunas')->count(),
            'count_sebagian'        => PasokanTbs::where('status_pembayaran', 'dibayar_sebagian')->count(),
            'count_belum'           => PasokanTbs::where('status_pembayaran', 'belum_dibayar')->count(),
            'top_petani'            => Petani::withCount('pasokanTbs')
                ->withSum('pasokanTbs as total_netto', 'berat_netto')
                ->orderByDesc('total_netto')
                ->take(4)
                ->get(),
            'pasokan_terbaru'       => PasokanTbs::with('petani')
                ->latest('tanggal')
                ->latest('id')
                ->take(4)
                ->get(),
            'distribusi_pks'        => PksPembeli::withCount('pengirimanTbs')
                ->orderByDesc('pengiriman_tbs_count')
                ->take(3)
                ->get(),
        ];

        return view('dashboard', compact('stats'));
    })->name('dashboard');

    Route::resource('petani', PetaniController::class);
    Route::resource('pks-pembeli', PksPembeliController::class);
    Route::resource('pasokan-tbs', PasokanTbsController::class);
    Route::resource('pengiriman-tbs', PengirimanTbsController::class);
    Route::resource('hasil-timbang-pks', HasilTimbangPksController::class);

    Route::middleware('role:pemilik')->group(function () {
        Route::resource('harga-beli', HargaBeliController::class);
    });
});
