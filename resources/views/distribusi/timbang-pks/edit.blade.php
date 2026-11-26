@extends('app')

@section('content')
@php
    $totalKirim = (float) ($hasilTimbang->pengiriman?->detailPengiriman?->sum('berat_dimuat') ?? 0);
@endphp
<div class="main-card mb-3 card shadow-sm border-0">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Edit Nota Hasil Timbang PKS</span>
        <span class="badge bg-primary">{{ $hasilTimbang->no_nota_timbang }}</span>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('hasil-timbang-pks.update', $hasilTimbang->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-5 mb-3">
                    <label class="form-label">Kode Pengiriman TBS</label>
                    <input type="text" class="form-control bg-light" value="{{ $hasilTimbang->pengiriman->no_surat_jalan ?? '-' }} ({{ $hasilTimbang->pengiriman->pks->nama ?? '-' }})" readonly>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Nomor Nota Timbang PKS <span class="text-danger">*</span></label>
                    <input type="text" name="no_nota_timbang" class="form-control" value="{{ old('no_nota_timbang', $hasilTimbang->no_nota_timbang) }}" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Total Berat Kirim (Pengepul)</label>
                    <div class="input-group">
                        <input type="text" id="total_berat_kirim" class="form-control bg-light fw-bold" value="{{ number_format($totalKirim, 2, '.', '') }}" readonly>
                        <span class="input-group-text">kg</span>
                    </div>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Berat Bruto <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" step="0.01" min="0.01" name="bruto" id="bruto" class="form-control" value="{{ old('bruto', $hasilTimbang->bruto) }}" required>
                        <span class="input-group-text">kg</span>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Berat Tara <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" step="0.01" min="0" name="tara" id="tara" class="form-control" value="{{ old('tara', $hasilTimbang->tara) }}" required>
                        <span class="input-group-text">kg</span>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Berat Netto Pabrik</label>
                    <div class="input-group">
                        <input type="number" step="0.01" id="netto" class="form-control bg-light fw-bold" value="{{ $hasilTimbang->netto }}" readonly>
                        <span class="input-group-text">kg</span>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Potongan Rafaksi PKS</label>
                    <div class="input-group">
                        <input type="number" step="0.01" min="0" name="rafaksi" id="rafaksi" class="form-control" value="{{ old('rafaksi', $hasilTimbang->rafaksi) }}">
                        <span class="input-group-text">kg</span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Berat Dibayar PKS</label>
                    <div class="input-group">
                        <input type="number" step="0.01" id="berat_dibayar" class="form-control bg-light fw-bold text-primary" value="{{ $hasilTimbang->berat_dibayar }}" readonly>
                        <span class="input-group-text">kg</span>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Indikator Selisih Berat</label>
                    <div class="input-group">
                        <input type="text" id="selisih_berat_view" class="form-control bg-light fw-bold" value="0.00" readonly>
                        <span class="input-group-text">kg</span>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Harga Jual PKS <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" step="0.01" min="0" name="harga_jual" id="harga_jual" class="form-control" value="{{ old('harga_jual', $hasilTimbang->harga_jual) }}" required>
                        <span class="input-group-text">/ kg</span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-success">Total Nilai Penjualan</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light fw-bold text-success">Rp</span>
                        <input type="text" id="nilai_penjualan_view" class="form-control bg-light fw-bold text-success fs-5" value="{{ number_format($hasilTimbang->nilai_penjualan, 0, ',', '.') }}" readonly>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Perbarui Nota Timbang</button>
                <a href="{{ route('hasil-timbang-pks.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const totalKirim = parseFloat(document.getElementById('total_berat_kirim').value) || 0;
    const brutoEl = document.getElementById('bruto');
    const taraEl = document.getElementById('tara');
    const nettoEl = document.getElementById('netto');
    const rafaksiEl = document.getElementById('rafaksi');
    const beratDibayarEl = document.getElementById('berat_dibayar');
    const selisihViewEl = document.getElementById('selisih_berat_view');
    const hargaJualEl = document.getElementById('harga_jual');
    const nilaiPenjualanViewEl = document.getElementById('nilai_penjualan_view');

    function hitungTimbangPks() {
        const bruto = parseFloat(brutoEl.value) || 0;
        const tara = parseFloat(taraEl.value) || 0;
        const rafaksi = parseFloat(rafaksiEl.value) || 0;
        const hargaJual = parseFloat(hargaJualEl.value) || 0;

        const netto = Math.max(0, bruto - tara);
        const beratDibayar = Math.max(0, netto - rafaksi);
        const selisih = beratDibayar - totalKirim;
        const nilaiPenjualan = beratDibayar * hargaJual;

        nettoEl.value = netto.toFixed(2);
        beratDibayarEl.value = beratDibayar.toFixed(2);
        selisihViewEl.value = (selisih >= 0 ? '+' : '') + selisih.toFixed(2);

        selisihViewEl.classList.remove('text-danger', 'text-success');
        if (selisih < 0) {
            selisihViewEl.classList.add('text-danger');
        } else if (selisih > 0) {
            selisihViewEl.classList.add('text-success');
        }

        nilaiPenjualanViewEl.value = new Intl.NumberFormat('id-ID', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 2
        }).format(nilaiPenjualan);
    }

    [brutoEl, taraEl, rafaksiEl, hargaJualEl].forEach(function (el) {
        if (el) {
            el.addEventListener('input', hitungTimbangPks);
        }
    });

    hitungTimbangPks();
});
</script>
@endpush
