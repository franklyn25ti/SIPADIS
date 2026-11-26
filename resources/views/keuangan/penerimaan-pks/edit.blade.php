@extends('app')

@section('content')
<div class="main-card mb-3 card shadow-sm border-0">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Catat Penerimaan Dana Masuk dari PKS</span>
        <span class="badge bg-primary">{{ $nota->no_nota_timbang }}</span>
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

        <div class="row mb-3">
            <div class="col-md-4 mb-2">
                <label class="form-label small text-muted mb-1">Pabrik Tujuan (PKS)</label>
                <input type="text" class="form-control bg-light fw-semibold" value="{{ $nota->pengiriman?->pks?->nama ?? '-' }}" readonly>
            </div>
            <div class="col-md-4 mb-2">
                <label class="form-label small text-muted mb-1">Kode Pengiriman</label>
                <input type="text" class="form-control bg-light" value="{{ $nota->pengiriman->no_surat_jalan ?? '-' }}" readonly>
            </div>
            <div class="col-md-4 mb-2">
                <label class="form-label small text-muted mb-1">Berat Dibayar PKS</label>
                <input type="text" class="form-control bg-light" value="{{ number_format($nota->berat_dibayar, 2, ',', '.') }} kg" readonly>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4 mb-2">
                <label class="form-label">Total Nilai Penjualan</label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="text" class="form-control bg-light fw-semibold" value="{{ number_format($nota->nilai_penjualan, 0, ',', '.') }}" readonly>
                </div>
            </div>
            <div class="col-md-4 mb-2">
                <label class="form-label">Dana Sudah Diterima</label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="text" class="form-control bg-light fw-semibold text-success" value="{{ number_format($nota->jumlah_diterima, 0, ',', '.') }}" readonly>
                </div>
            </div>
            <div class="col-md-4 mb-2">
                <label class="form-label fw-bold text-danger">Sisa Piutang PKS</label>
                <div class="input-group">
                    <span class="input-group-text bg-light fw-bold text-danger">Rp</span>
                    <input type="text" class="form-control bg-light fw-bold text-danger" value="{{ number_format($nota->sisa_piutang, 0, ',', '.') }}" readonly>
                </div>
            </div>
        </div>

        <hr>

        <form action="{{ route('penerimaan-pks.update', $nota->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Terima Dana <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_terima_dana" class="form-control" value="{{ old('tanggal_terima_dana', now()->toDateString()) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label d-flex justify-content-between align-items-center">
                        <span>Jumlah Dana Masuk <span class="text-danger">*</span></span>
                        <button type="button" id="btnLunasiPiutang" class="btn btn-sm btn-outline-primary py-0 px-2">Lunasi Sisa Piutang</button>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" step="0.01" min="0.01" name="jumlah_masuk" id="jumlah_masuk" class="form-control" value="{{ old('jumlah_masuk') }}" placeholder="0" required>
                    </div>
                    <div class="mt-1 small" id="preview_status_piutang">
                        <span class="text-muted">Masukkan nominal dana masuk untuk melihat estimasi sisa piutang.</span>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-check me-1"></i> Simpan Penerimaan Dana
                </button>
                <a href="{{ route('penerimaan-pks.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const nilaiPenjualan = {{ (float) $nota->nilai_penjualan }};
    const sudahDiterima = {{ (float) $nota->jumlah_diterima }};
    const sisaPiutang = {{ (float) $nota->sisa_piutang }};
    const jumlahMasukInput = document.getElementById('jumlah_masuk');
    const btnLunasiPiutang = document.getElementById('btnLunasiPiutang');
    const previewEl = document.getElementById('preview_status_piutang');

    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 2
        }).format(angka);
    }

    function updatePreview() {
        const masuk = parseFloat(jumlahMasukInput.value) || 0;
        if (masuk <= 0) {
            previewEl.innerHTML = '<span class="text-muted">Masukkan nominal dana masuk untuk melihat estimasi sisa piutang.</span>';
            return;
        }

        const totalBaru = sudahDiterima + masuk;
        const sisaBaru = Math.max(0, nilaiPenjualan - totalBaru);

        if (totalBaru >= nilaiPenjualan) {
            previewEl.innerHTML = '<span class="badge bg-success">Lunas</span> <span class="text-muted ms-1">Piutang nota PKS ini akan lunas sepenuhnya.</span>';
        } else {
            previewEl.innerHTML = '<span class="badge bg-warning text-dark">Diterima Sebagian</span> <span class="text-muted ms-1">Sisa piutang setelah diterima: Rp ' + formatRupiah(sisaBaru) + '</span>';
        }
    }

    jumlahMasukInput.addEventListener('input', updatePreview);

    if (btnLunasiPiutang) {
        btnLunasiPiutang.addEventListener('click', function () {
            if (sisaPiutang > 0) {
                jumlahMasukInput.value = sisaPiutang.toFixed(2);
                updatePreview();
            }
        });
    }

    updatePreview();
});
</script>
@endpush
