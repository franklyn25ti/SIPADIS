@extends('app')

@section('content')
<div class="main-card mb-3 card shadow-sm border-0">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Form Pencatatan Pembayaran Petani</span>
        <a href="{{ route('pembayaran-petani.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fa fa-arrow-left me-1"></i> Kembali
        </a>
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

        <form action="{{ route('pembayaran-petani.store') }}" method="POST" id="formPembayaranPetani">
            @csrf
            <div class="mb-3">
                <label class="form-label">Pilih Transaksi Pasokan Belum Lunas <span class="text-danger">*</span></label>
                <select name="pasokan_id" id="pasokan_id" class="form-select" required>
                    <option value="" data-nilai="0" data-dibayar="0" data-sisa="0">-- Pilih Transaksi Pasokan TBS --</option>
                    @foreach($pasokanBelumLunas as $pasokan)
                        @php
                            $isSelected = (string) old('pasokan_id', $selectedPasokanId) === (string) $pasokan->id;
                        @endphp
                        <option value="{{ $pasokan->id }}"
                                data-nilai="{{ (float) $pasokan->nilai_pembelian }}"
                                data-dibayar="{{ (float) $pasokan->total_dibayar }}"
                                data-sisa="{{ (float) $pasokan->sisa_tagihan }}"
                                {{ $isSelected ? 'selected' : '' }}>
                            {{ $pasokan->no_transaksi }} | {{ $pasokan->petani->nama_petani ?? '-' }} | {{ $pasokan->tanggal->format('d/m/Y') }} | Sisa: Rp {{ number_format($pasokan->sisa_tagihan, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Total Nilai Pembelian</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="text" id="nilai_pembelian_view" class="form-control bg-light fw-semibold" value="0" readonly>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Sudah Dibayar Sebelumnya</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="text" id="sudah_dibayar_view" class="form-control bg-light fw-semibold text-success" value="0" readonly>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold text-danger">Sisa Tagihan</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light fw-bold text-danger">Rp</span>
                        <input type="text" id="sisa_tagihan_view" class="form-control bg-light fw-bold text-danger" value="0" readonly>
                    </div>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Tanggal Pembayaran <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_bayar" class="form-control" value="{{ old('tanggal_bayar', now()->toDateString()) }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label d-flex justify-content-between align-items-center">
                        <span>Jumlah Dibayar <span class="text-danger">*</span></span>
                        <button type="button" id="btnLunasiSisa" class="btn btn-sm btn-outline-primary py-0 px-2">Lunasi Sisa Tagihan</button>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" step="0.01" min="0.01" name="jumlah_bayar" id="jumlah_bayar" class="form-control" value="{{ old('jumlah_bayar') }}" placeholder="0" required>
                    </div>
                    <div class="mt-1 small" id="preview_status_bayar">
                        <span class="text-muted">Perkiraan status setelah simpan akan muncul di sini.</span>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                    <select name="metode_pembayaran" class="form-select" required>
                        <option value="Tunai" {{ old('metode_pembayaran', 'Tunai') === 'Tunai' ? 'selected' : '' }}>Tunai</option>
                        <option value="Transfer Bank" {{ old('metode_pembayaran') === 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank</option>
                        <option value="Lainnya" {{ old('metode_pembayaran') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="2" placeholder="Catatan pembayaran bertahap, nomor bukti transfer, dll.">{{ old('keterangan') }}</textarea>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-success" {{ $pasokanBelumLunas->isEmpty() ? 'disabled' : '' }}>
                    <i class="fa fa-check me-1"></i> Simpan Pembayaran Petani
                </button>
                <a href="{{ route('pembayaran-petani.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const pasokanSelect = document.getElementById('pasokan_id');
    const nilaiView = document.getElementById('nilai_pembelian_view');
    const dibayarView = document.getElementById('sudah_dibayar_view');
    const sisaView = document.getElementById('sisa_tagihan_view');
    const jumlahBayarInput = document.getElementById('jumlah_bayar');
    const btnLunasiSisa = document.getElementById('btnLunasiSisa');
    const previewStatus = document.getElementById('preview_status_bayar');

    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 2
        }).format(angka);
    }

    function getSelectedData() {
        const opt = pasokanSelect.options[pasokanSelect.selectedIndex];
        if (!opt || !opt.value) {
            return { nilai: 0, dibayar: 0, sisa: 0, hasSelection: false };
        }
        return {
            nilai: parseFloat(opt.getAttribute('data-nilai')) || 0,
            dibayar: parseFloat(opt.getAttribute('data-dibayar')) || 0,
            sisa: parseFloat(opt.getAttribute('data-sisa')) || 0,
            hasSelection: true
        };
    }

    function updateSummaryAndPreview() {
        const data = getSelectedData();
        nilaiView.value = formatRupiah(data.nilai);
        dibayarView.value = formatRupiah(data.dibayar);
        sisaView.value = formatRupiah(data.sisa);

        if (!data.hasSelection) {
            previewStatus.innerHTML = '<span class="text-muted">Pilih transaksi pasokan terlebih dahulu.</span>';
            return;
        }

        const bayarBaru = parseFloat(jumlahBayarInput.value) || 0;
        if (bayarBaru <= 0) {
            previewStatus.innerHTML = '<span class="text-muted">Masukkan nominal pembayaran untuk melihat status akhir.</span>';
            return;
        }

        const totalAkumulasi = data.dibayar + bayarBaru;
        const sisaAkhir = Math.max(0, data.nilai - totalAkumulasi);

        if (totalAkumulasi >= data.nilai) {
            previewStatus.innerHTML = '<span class="badge bg-success">Lunas</span> <span class="text-muted ms-1">Tagihan pasokan ini akan lunas sepenuhnya.</span>';
        } else {
            previewStatus.innerHTML = '<span class="badge bg-warning text-dark">Dibayar Sebagian</span> <span class="text-muted ms-1">Sisa tagihan setelah bayar: Rp ' + formatRupiah(sisaAkhir) + '</span>';
        }
    }

    pasokanSelect.addEventListener('change', updateSummaryAndPreview);
    jumlahBayarInput.addEventListener('input', updateSummaryAndPreview);

    if (btnLunasiSisa) {
        btnLunasiSisa.addEventListener('click', function () {
            const data = getSelectedData();
            if (data.hasSelection && data.sisa > 0) {
                jumlahBayarInput.value = data.sisa.toFixed(2);
                updateSummaryAndPreview();
            }
        });
    }

    updateSummaryAndPreview();
});
</script>
@endpush
