@extends('app')

@section('content')
<div class="main-card mb-3 card shadow-sm border-0">
    <div class="card-header">Form Input Nota Hasil Timbang PKS</div>
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

        <form action="{{ route('hasil-timbang-pks.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-5 mb-3">
                    <label class="form-label">Pilih Pengiriman TBS (Status: Dikirim) <span class="text-danger">*</span></label>
                    <select name="pengiriman_id" id="pengiriman_id" class="form-select" required>
                        <option value="" data-total-kirim="0">-- Pilih Pengiriman TBS --</option>
                        @foreach($pengirimanList as $p)
                            @php
                                $totalKirim = (float) $p->detailPengiriman->sum('berat_dimuat');
                                $isSelected = (string) old('pengiriman_id', $selectedPengirimanId) === (string) $p->id;
                            @endphp
                            <option value="{{ $p->id }}" data-total-kirim="{{ $totalKirim }}" {{ $isSelected ? 'selected' : '' }}>
                                {{ $p->no_surat_jalan }} | {{ $p->pks->nama ?? '-' }} | Muatan: {{ number_format($totalKirim, 2, ',', '.') }} kg ({{ $p->sopir }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Nomor Nota Timbang PKS <span class="text-danger">*</span></label>
                    <input type="text" name="no_nota_timbang" class="form-control" value="{{ old('no_nota_timbang', $suggestedNoNota) }}" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Total Berat Kirim (Pengepul)</label>
                    <div class="input-group">
                        <input type="text" id="total_berat_kirim_view" class="form-control bg-light fw-bold" value="0.00" readonly>
                        <span class="input-group-text">kg</span>
                    </div>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Berat Bruto (Truk + TBS) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" step="0.01" min="0.01" name="bruto" id="bruto" class="form-control" value="{{ old('bruto') }}" placeholder="0.00" required>
                        <span class="input-group-text">kg</span>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Berat Tara (Truk Kosong) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="number" step="0.01" min="0" name="tara" id="tara" class="form-control" value="{{ old('tara') }}" placeholder="0.00" required>
                        <span class="input-group-text">kg</span>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Berat Netto Pabrik</label>
                    <div class="input-group">
                        <input type="number" step="0.01" id="netto" class="form-control bg-light fw-bold" value="0.00" readonly>
                        <span class="input-group-text">kg</span>
                    </div>
                    <small class="text-muted">Otomatis: Bruto - Tara</small>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Potongan Rafaksi PKS</label>
                    <div class="input-group">
                        <input type="number" step="0.01" min="0" name="rafaksi" id="rafaksi" class="form-control" value="{{ old('rafaksi', 0) }}" placeholder="0.00">
                        <span class="input-group-text">kg</span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Berat Dibayar PKS</label>
                    <div class="input-group">
                        <input type="number" step="0.01" id="berat_dibayar" class="form-control bg-light fw-bold text-primary" value="0.00" readonly>
                        <span class="input-group-text">kg</span>
                    </div>
                    <small class="text-muted">Otomatis: Netto - Rafaksi</small>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Indikator Selisih Berat (PKS vs Pengepul)</label>
                    <div class="input-group">
                        <input type="text" id="selisih_berat_view" class="form-control bg-light fw-bold" value="0.00" readonly>
                        <span class="input-group-text">kg</span>
                    </div>
                    <small id="selisih_badge_info" class="text-muted">Berat Dibayar PKS - Total Berat Kirim</small>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Harga Jual PKS <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" step="0.01" min="0" name="harga_jual" id="harga_jual" class="form-control" value="{{ old('harga_jual') }}" placeholder="Contoh: 2750" required>
                        <span class="input-group-text">/ kg</span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-success">Total Nilai Penjualan (Tagihan ke PKS)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light fw-bold text-success">Rp</span>
                        <input type="text" id="nilai_penjualan_view" class="form-control bg-light fw-bold text-success fs-5" value="0" readonly>
                    </div>
                    <small class="text-muted">Otomatis: Berat Dibayar &times; Harga Jual</small>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-success" {{ $pengirimanList->isEmpty() ? 'disabled' : '' }}>
                    <i class="fa fa-check me-1"></i> Simpan Nota Timbang & Selesaikan Pengiriman
                </button>
                <a href="{{ route('hasil-timbang-pks.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const pengirimanEl = document.getElementById('pengiriman_id');
    const totalKirimViewEl = document.getElementById('total_berat_kirim_view');
    const brutoEl = document.getElementById('bruto');
    const taraEl = document.getElementById('tara');
    const nettoEl = document.getElementById('netto');
    const rafaksiEl = document.getElementById('rafaksi');
    const beratDibayarEl = document.getElementById('berat_dibayar');
    const selisihViewEl = document.getElementById('selisih_berat_view');
    const selisihInfoEl = document.getElementById('selisih_badge_info');
    const hargaJualEl = document.getElementById('harga_jual');
    const nilaiPenjualanViewEl = document.getElementById('nilai_penjualan_view');

    function hitungTimbangPks() {
        const selectedOption = pengirimanEl.options[pengirimanEl.selectedIndex];
        const totalKirim = selectedOption ? (parseFloat(selectedOption.getAttribute('data-total-kirim')) || 0) : 0;
        totalKirimViewEl.value = totalKirim.toFixed(2);

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
            selisihInfoEl.innerHTML = '<span class="badge bg-danger">Susut / Selisih Kurang: ' + selisih.toFixed(2) + ' kg</span>';
        } else if (selisih > 0) {
            selisihViewEl.classList.add('text-success');
            selisihInfoEl.innerHTML = '<span class="badge bg-success">Surplus Berat: +' + selisih.toFixed(2) + ' kg</span>';
        } else {
            selisihInfoEl.textContent = 'Berat sesuai dengan muatan kirim';
        }

        nilaiPenjualanViewEl.value = new Intl.NumberFormat('id-ID', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 2
        }).format(nilaiPenjualan);
    }

    if (pengirimanEl) {
        pengirimanEl.addEventListener('change', hitungTimbangPks);
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
