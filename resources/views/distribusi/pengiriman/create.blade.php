@extends('app')

@section('content')
<div class="main-card mb-3 card shadow-sm border-0">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Form Pencatatan Pengiriman TBS ke PKS (Multi-Petani)</span>
        <span class="badge bg-primary">Kode Pengiriman: {{ $noSuratJalan }}</span>
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

        <form action="{{ route('pengiriman-tbs.store') }}" method="POST" id="formPengiriman">
            @csrf
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Kode Pengiriman <span class="text-danger">*</span></label>
                    <input type="text" name="no_surat_jalan" class="form-control" value="{{ old('no_surat_jalan', $noSuratJalan) }}" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Tanggal Kirim <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_kirim" class="form-control" value="{{ old('tanggal_kirim', now()->toDateString()) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Pabrik Tujuan (PKS) <span class="text-danger">*</span></label>
                    <select name="pks_id" class="form-select" required>
                        <option value="">-- Pilih PKS Tujuan --</option>
                        @foreach($pksList as $pks)
                            <option value="{{ $pks->id }}" {{ (string) old('pks_id') === (string) $pks->id ? 'selected' : '' }}>
                                {{ $pks->nama }} {{ $pks->alamat ? ' - '.$pks->alamat : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Kendaraan / Nomor Polisi Truk <span class="text-danger">*</span></label>
                    <input type="text" name="kendaraan" class="form-control" value="{{ old('kendaraan') }}" placeholder="Contoh: Mitsubishi Canter BM 8821 TU" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Sopir <span class="text-danger">*</span></label>
                    <input type="text" name="sopir" class="form-control" value="{{ old('sopir') }}" placeholder="Masukkan nama sopir pengangkut" required>
                </div>
            </div>

            <hr>

            <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    <h6 class="fw-bold mb-0">Pilih Pasokan TBS Petani yang Dimuat ke Truk <span class="text-danger">*</span></h6>
                    <small class="text-muted">Centang satu atau beberapa transaksi pasokan TBS yang diangkut dalam pengiriman ini.</small>
                </div>
                <div class="bg-light border rounded px-3 py-2 text-end">
                    <span class="small text-muted d-block">Total Berat Dimuat ke Truk:</span>
                    <span class="fs-5 fw-bolder text-success"><span id="totalBeratMuatView">0,00</span> kg</span>
                </div>
            </div>

            <div class="table-responsive border rounded mb-3">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 55px;">
                                <input type="checkbox" class="form-check-input" id="checkAllPasokan" title="Pilih Semua">
                            </th>
                            <th>No. Transaksi</th>
                            <th>Tanggal Timbang</th>
                            <th>Nama Petani</th>
                            <th class="text-end">Berat Netto (kg)</th>
                            <th class="text-end">Sisa Belum Terkirim (kg)</th>
                            <th style="width: 220px;">Berat Dimuat ke Truk (kg)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pasokanTersedia as $pasokan)
                        @php
                            $isChecked = is_array(old('pasokan_ids')) && in_array($pasokan->id, old('pasokan_ids'));
                        @endphp
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" class="form-check-input pasokan-checkbox" name="pasokan_ids[]" value="{{ $pasokan->id }}" id="chk_{{ $pasokan->id }}" {{ $isChecked ? 'checked' : '' }}>
                            </td>
                            <td class="fw-bold text-primary">
                                <label for="chk_{{ $pasokan->id }}" class="cursor-pointer mb-0">{{ $pasokan->no_transaksi }}</label>
                            </td>
                            <td>{{ $pasokan->tanggal->format('d/m/Y') }}</td>
                            <td class="fw-semibold">{{ $pasokan->petani->nama_petani ?? '-' }}</td>
                            <td class="text-end">{{ number_format($pasokan->berat_netto, 2, ',', '.') }} kg</td>
                            <td class="text-end fw-bold text-success">{{ number_format($pasokan->sisa_berat, 2, ',', '.') }} kg</td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <input type="number" step="0.01" min="0.01" max="{{ $pasokan->sisa_berat }}"
                                           name="berat_dimuat[{{ $pasokan->id }}]"
                                           class="form-control berat-dimuat-input"
                                           data-id="{{ $pasokan->id }}"
                                           value="{{ old("berat_dimuat.{$pasokan->id}", $pasokan->sisa_berat) }}"
                                           {{ $isChecked ? '' : 'disabled' }}>
                                    <span class="input-group-text">kg</span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Tidak ada stok pasokan TBS yang belum dikirim. Silakan catat <a href="{{ route('pasokan-tbs.create') }}">Pasokan TBS</a> terlebih dahulu.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-success" {{ $pasokanTersedia->isEmpty() ? 'disabled' : '' }}>
                    <i class="fa fa-check me-1"></i> Simpan Pengiriman TBS
                </button>
                <a href="{{ route('pengiriman-tbs.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const checkAll = document.getElementById('checkAllPasokan');
    const checkboxes = document.querySelectorAll('.pasokan-checkbox');
    const totalView = document.getElementById('totalBeratMuatView');

    function updateTotalMuatan() {
        let total = 0;
        checkboxes.forEach(function (chk) {
            const input = document.querySelector(`.berat-dimuat-input[data-id="${chk.value}"]`);
            if (chk.checked) {
                input.removeAttribute('disabled');
                total += parseFloat(input.value) || 0;
            } else {
                input.setAttribute('disabled', 'disabled');
            }
        });

        totalView.textContent = new Intl.NumberFormat('id-ID', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(total);
    }

    if (checkAll) {
        checkAll.addEventListener('change', function () {
            checkboxes.forEach(function (chk) {
                chk.checked = checkAll.checked;
            });
            updateTotalMuatan();
        });
    }

    checkboxes.forEach(function (chk) {
        chk.addEventListener('change', updateTotalMuatan);
    });

    document.querySelectorAll('.berat-dimuat-input').forEach(function (input) {
        input.addEventListener('input', updateTotalMuatan);
    });

    updateTotalMuatan();
});
</script>
@endpush
