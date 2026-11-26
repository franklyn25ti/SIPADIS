@extends('app')

@section('content')
<div class="main-card mb-3 card shadow-sm border-0">
    <div class="card-header">Edit Data Pasokan TBS Petani</div>
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

        <form action="{{ route('pasokan-tbs.update', $pasokan->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Pasokan <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', $pasokan->tanggal->toDateString()) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Petani Mitra <span class="text-danger">*</span></label>
                    <select name="petani_id" class="form-select" required>
                        @foreach($petaniAktif as $petani)
                            <option value="{{ $petani->id }}" {{ (string) old('petani_id', $pasokan->petani_id) === (string) $petani->id ? 'selected' : '' }}>
                                {{ $petani->nama_petani }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Berat Pasokan TBS <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" step="0.01" min="0.01" name="berat_timbang" id="berat_timbang" class="form-control" value="{{ old('berat_timbang', $pasokan->berat_timbang) }}" required>
                        <span class="input-group-text">kg</span>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Harga Beli TBS <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" step="0.01" min="0" name="harga_beli" id="harga_beli" class="form-control" value="{{ old('harga_beli', $pasokan->harga_beli) }}" required>
                        <span class="input-group-text">/ kg</span>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold text-success">Total Nilai Pembelian</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light fw-bold text-success">Rp</span>
                        <input type="text" id="nilai_pembelian_view" class="form-control bg-light fw-bold text-success fs-5" value="{{ number_format($pasokan->nilai_pembelian, 0, ',', '.') }}" readonly>
                    </div>
                    <small class="text-muted">Otomatis: Berat Pasokan &times; Harga Beli</small>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Perbarui Data Pasokan</button>
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
    const hargaBeliEl = document.getElementById('harga_beli');
    const nilaiPembelianViewEl = document.getElementById('nilai_pembelian_view');

    function hitungPasokan() {
        const beratTimbang = Math.max(0, parseFloat(beratTimbangEl.value) || 0);
        const hargaBeli = Math.max(0, parseFloat(hargaBeliEl.value) || 0);
        const nilaiPembelian = beratTimbang * hargaBeli;

        nilaiPembelianViewEl.value = new Intl.NumberFormat('id-ID', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 2
        }).format(nilaiPembelian);
    }

    [beratTimbangEl, hargaBeliEl].forEach(function (el) {
        if (el) {
            el.addEventListener('input', hitungPasokan);
        }
    });

    hitungPasokan();
});
</script>
@endpush
