@extends('app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <a href="{{ route('pengiriman-tbs.index') }}" class="btn btn-secondary">
            <i class="fa fa-arrow-left me-1"></i> Kembali ke Daftar Pengiriman
        </a>
    </div>
    <div>
        @if($pengiriman->status_pengiriman === 'dikirim')
            <a href="{{ route('hasil-timbang-pks.create', ['pengiriman_id' => $pengiriman->id]) }}" class="btn btn-success">
                <i class="fa fa-balance-scale me-1"></i> Input Hasil Timbang PKS
            </a>
        @endif
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="main-card mb-3 card shadow-sm border-0">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Detail Pengiriman TBS ke PKS</span>
        <span class="badge {{ $pengiriman->status_pengiriman === 'selesai_timbang' ? 'bg-success' : 'bg-warning' }}">
            {{ $pengiriman->status_pengiriman === 'selesai_timbang' ? 'Selesai Timbang PKS' : 'Dikirim' }}
        </span>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-sm-6">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted" style="width: 150px;">Kode Pengiriman</td>
                        <td class="fw-bold text-primary">: {{ $pengiriman->no_surat_jalan }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Tanggal Kirim</td>
                        <td class="fw-semibold">: {{ $pengiriman->tanggal_kirim->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Kendaraan / Truk</td>
                        <td class="fw-semibold">: {{ $pengiriman->kendaraan }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Nama Sopir</td>
                        <td class="fw-semibold">: {{ $pengiriman->sopir }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-sm-6">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted" style="width: 150px;">PKS Tujuan</td>
                        <td class="fw-bold">: {{ $pengiriman->pks->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Alamat Pabrik</td>
                        <td>: {{ $pengiriman->pks->alamat ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Kontak PKS</td>
                        <td>: {{ $pengiriman->pks->no_kontak ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <h6 class="fw-bold mb-2">Rincian Muatan Pasokan TBS Petani:</h6>
        <div class="table-responsive mb-3">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 50px;">No</th>
                        <th>No. Transaksi Pasokan</th>
                        <th>Tanggal Timbang</th>
                        <th>Nama Petani Asal</th>
                        <th>Lokasi / Alamat Kebun</th>
                        <th class="text-end">Berat Dimuat (kg)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pengiriman->detailPengiriman as $detail)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="fw-semibold">{{ $detail->pasokan->no_transaksi ?? '-' }}</td>
                        <td>{{ $detail->pasokan?->tanggal?->format('d/m/Y') ?? '-' }}</td>
                        <td class="fw-bold">{{ $detail->pasokan?->petani?->nama_petani ?? '-' }}</td>
                        <td>{{ $detail->pasokan?->petani?->alamat ?? '-' }}</td>
                        <td class="text-end fw-bold">{{ number_format($detail->berat_dimuat, 2, ',', '.') }} kg</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light fw-bold">
                    <tr>
                        <td colspan="5" class="text-end">Total Tonase Dimuat dari Pengepul:</td>
                        <td class="text-end fs-6 text-success">
                            {{ number_format($pengiriman->detailPengiriman->sum('berat_dimuat'), 2, ',', '.') }} kg
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        @if($pengiriman->hasilTimbangPks)
        @php
            $ht = $pengiriman->hasilTimbangPks;
            $totalMuat = (float) $pengiriman->detailPengiriman->sum('berat_dimuat');
            $selisih = (float) $ht->berat_dibayar - $totalMuat;
        @endphp
        <div class="alert alert-info mb-0">
            <h6 class="fw-bold mb-1">Ringkasan Hasil Timbang PKS (Nota: {{ $ht->no_nota_timbang }})</h6>
            <div class="row small">
                <div class="col-md-3">Bruto: <strong>{{ number_format($ht->bruto, 2, ',', '.') }} kg</strong> | Tara: <strong>{{ number_format($ht->tara, 2, ',', '.') }} kg</strong></div>
                <div class="col-md-3">Netto: <strong>{{ number_format($ht->netto, 2, ',', '.') }} kg</strong> | Rafaksi: <strong>{{ number_format($ht->rafaksi, 2, ',', '.') }} kg</strong></div>
                <div class="col-md-3">Berat Dibayar: <strong>{{ number_format($ht->berat_dibayar, 2, ',', '.') }} kg</strong></div>
                <div class="col-md-3">Selisih Berat: <strong class="{{ $selisih < 0 ? 'text-danger' : 'text-success' }}">{{ $selisih >= 0 ? '+' : '' }}{{ number_format($selisih, 2, ',', '.') }} kg</strong></div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
