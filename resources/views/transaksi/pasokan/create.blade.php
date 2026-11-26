@extends('app')

@section('content')
<div class="main-card mb-3 card shadow-sm border-0">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Form Pencatatan Pasokan & Timbangan Masuk TBS</span>
        <span class="badge bg-primary">No. Otomatis: {{ $noTransaksi }}</span>
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

        <form action="{{ route('pasokan-tbs.store') }}" method="POST" id="formPasokan">
            @csrf
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Nomor Transaksi (Otomatis)</label>
                    <input type="text" class="form-control bg-light" value="{{ $noTransaksi }}" readonly>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Tanggal Timbang <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', now()->toDateString()) }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Petani Mitra (Aktif) <span class="text-danger">*</span></label>
                    <select name="petani_id" class="form-select" required>
                        <option value="">-- Pilih Petani --</option>
                        @foreach($petaniAktif as $petani)
                            <option value="{{ $petani->id }}" {{ (string) old('petani_id') === (string) $petani->id ? 'selected' : '' }}>
                                {{ $petani->nama_petani }} {{ $petani->alamat ? '('.$petani->alamat.')' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Berat Timbang Kotor <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" step="0.01" min="0.01" name="berat_timbang" id="berat_timbang" class="form-control" value="{{ old('berat_timbang') }}" placeholder="0.00" required>
                        <span class="input-group-text">kg</span>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Potongan / Sortasi Timbangan</label>
                    <div class="input-group">
                        <input type="number" step="0.01" min="0" name="potongan" id="potongan" class="form-control" value="{{ old('potongan', 0) }}" placeholder="0.00">
                        <span class="input-group-text">kg</span>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Berat Netto (Bersih)</label>
                    <div class="input-group">
                        <input type="number" step="0.01" id="berat_netto" class="form-control bg-light fw-bold" value="0.00" readonly>
                        <span class="input-group-text">kg</span>
                    </div>
                    <small class="text-muted">Otomatis: Berat Timbang - Potongan</small>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Harga Beli TBS Hari Ini <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" step="0.01" min="0" name="harga_beli" id="harga_beli" class="form-control" value="{{ old('harga_beli', $hargaHariIni) }}" required>
                        <span class="input-group-text">/ kg</span>
                    </div>
                    <small class="text-muted">Otomatis terisi dari penetapan harga harian (dapat disesuaikan bila ada kebijakan khusus).</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-success">Total Nilai Pembelian (Tagihan Petani)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light fw-bold text-success">Rp</span>
                        <input type="text" id="nilai_pembelian_view" class="form-control bg-light fw-bold text-success fs-5" value="0" readonly>
                    </div>
                    <small class="text-muted">Otomatis: Berat Netto &times; Harga Beli</small>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-check me-1"></i> Simpan Transaksi Pasokan
                </button>
                <a href="{{ route('pasokan-tbs.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const beratTimbangEl = document.getElementById('berat_timbang');
    const potonganEl = document.getElementById('potongan');
    const beratNettoEl = document.getElementById('berat_netto');
    const hargaBeliEl = document.getElementById('harga_beli');
    const nilaiPembelianViewEl = document.getElementById('nilai_pembelian_view');

    function hitungPasokan() {
        const beratTimbang = parseFloat(beratTimbangEl.value) || 0;
        const potongan = parseFloat(potonganEl.value) || 0;
        const hargaBeli = parseFloat(hargaBeliEl.value) || 0;

        const beratNetto = Math.max(0, beratTimbang - potongan);
        const nilaiPembelian = beratNetto * hargaBeli;

        beratNettoEl.value = beratNetto.toFixed(2);
        nilaiPembelianViewEl.value = new Intl.NumberFormat('id-ID', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 2
        }).format(nilaiPembelian);
    }

    [beratTimbangEl, potonganEl, hargaBeliEl].forEach(function (el) {
        if (el) {
            el.addEventListener('input', hitungPasokan);
        }
    });

    hitungPasokan();
});
</script>
@endpush
