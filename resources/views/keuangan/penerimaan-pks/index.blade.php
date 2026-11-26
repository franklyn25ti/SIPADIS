@extends('app')

@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-wallet icon-gradient bg-grow-early"></i>
            </div>
            <div>Penerimaan Dana & Piutang PKS
                <div class="page-title-subheading">Pantau tagihan penjualan ke Pabrik Kelapa Sawit (PKS) dan catat pembayaran dana masuk</div>
            </div>
        </div>
        <div class="page-title-actions">
            <a href="{{ route('hasil-timbang-pks.index') }}" class="btn btn-outline-primary btn-shadow">
                <i class="fa fa-balance-scale me-1"></i> Nota Timbang PKS
            </a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Ringkasan KPI -->
<div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
        <div class="q-kpi-card q-kpi-dark">
            <div class="d-flex justify-content-between align-items-start">
                <span class="q-kpi-icon"><i class="fa fa-hand-holding-usd"></i></span>
                <span class="badge bg-warning">Piutang PKS</span>
            </div>
            <div class="q-kpi-label">Total Sisa Piutang PKS</div>
            <div class="q-kpi-value">Rp {{ number_format($totalSisaPiutang, 0, ',', '.') }}</div>
            <div class="q-kpi-trend text-warning">
                <i class="fa fa-hourglass-half"></i>
                <span>Sisa dana yang belum diterima dari pabrik</span>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="q-kpi-card q-kpi-light">
            <div class="d-flex justify-content-between align-items-start">
                <span class="q-kpi-icon q-icon-amber"><i class="fa fa-file-invoice"></i></span>
            </div>
            <div class="q-kpi-label">Total Nilai Penjualan PKS</div>
            <div class="q-kpi-value">Rp {{ number_format($totalNilaiPenjualan, 0, ',', '.') }}</div>
            <div class="q-kpi-trend text-muted">
                <span>Akumulasi tagihan nota timbang PKS</span>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="q-kpi-card q-kpi-light">
            <div class="d-flex justify-content-between align-items-start">
                <span class="q-kpi-icon"><i class="fa fa-check-circle"></i></span>
            </div>
            <div class="q-kpi-label">Total Dana Diterima</div>
            <div class="q-kpi-value text-success">Rp {{ number_format($totalDanaDiterima, 0, ',', '.') }}</div>
            <div class="q-kpi-trend text-success">
                <i class="fa fa-arrow-down"></i>
                <span>Total pembayaran masuk dari PKS</span>
            </div>
        </div>
    </div>
</div>

<div class="main-card mb-3 card shadow-sm border-0">
    <div class="card-header">Daftar Nota Timbang & Status Penerimaan Dana PKS</div>
    <div class="card-body">
        <form action="{{ route('penerimaan-pks.index') }}" method="GET" class="row g-2 mb-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label small text-muted mb-1">Filter PKS / Pabrik</label>
                <select name="pks_id" class="form-select">
                    <option value="">-- Semua PKS --</option>
                    @foreach($pksList as $pks)
                        <option value="{{ $pks->id }}" {{ (string) request('pks_id') === (string) $pks->id ? 'selected' : '' }}>
                            {{ $pks->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted mb-1">Status Penerimaan</label>
                <select name="status_penerimaan" class="form-select">
                    <option value="">-- Semua Status --</option>
                    <option value="belum_diterima" {{ request('status_penerimaan') === 'belum_diterima' ? 'selected' : '' }}>Belum Diterima</option>
                    <option value="diterima_sebagian" {{ request('status_penerimaan') === 'diterima_sebagian' ? 'selected' : '' }}>Diterima Sebagian</option>
                    <option value="lunas" {{ request('status_penerimaan') === 'lunas' ? 'selected' : '' }}>Lunas</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-filter me-1"></i> Filter
                </button>
                @if(request()->anyFilled(['pks_id', 'status_penerimaan']))
                    <a href="{{ route('penerimaan-pks.index') }}" class="btn btn-outline-secondary">Reset</a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="align-middle mb-0 table table-borderless table-striped table-hover">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>No. Nota Timbang</th>
                        <th>Kode Pengiriman & PKS</th>
                        <th class="text-end">Berat Dibayar (kg)</th>
                        <th class="text-end">Nilai Penjualan</th>
                        <th class="text-end">Sudah Diterima</th>
                        <th class="text-end">Sisa Piutang</th>
                        <th>Tgl Terima Terakhir</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($notaList as $nota)
                    <tr>
                        <td class="text-center text-muted">{{ $loop->iteration }}</td>
                        <td class="fw-bold text-primary">{{ $nota->no_nota_timbang }}</td>
                        <td>
                            <div class="fw-semibold">{{ $nota->pengiriman->no_surat_jalan ?? '-' }}</div>
                            <div class="small text-muted">{{ $nota->pengiriman?->pks?->nama ?? '-' }}</div>
                        </td>
                        <td class="text-end">{{ number_format($nota->berat_dibayar, 2, ',', '.') }} kg</td>
                        <td class="text-end fw-semibold">Rp {{ number_format($nota->nilai_penjualan, 0, ',', '.') }}</td>
                        <td class="text-end text-success">Rp {{ number_format($nota->jumlah_diterima, 0, ',', '.') }}</td>
                        <td class="text-end fw-bold text-danger">Rp {{ number_format($nota->sisa_piutang, 0, ',', '.') }}</td>
                        <td>{{ $nota->tanggal_terima_dana ? $nota->tanggal_terima_dana->format('d/m/Y') : '-' }}</td>
                        <td class="text-center">
                            @if($nota->status_penerimaan === 'lunas')
                                <span class="badge bg-success">Lunas</span>
                            @elseif($nota->status_penerimaan === 'diterima_sebagian')
                                <span class="badge bg-warning text-dark">Diterima Sebagian</span>
                            @else
                                <span class="badge bg-danger">Belum Diterima</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($nota->status_penerimaan !== 'lunas')
                                <a href="{{ route('penerimaan-pks.edit', $nota->id) }}" class="btn btn-success btn-sm">
                                    <i class="fa fa-plus-circle me-1"></i> Catat Dana Masuk
                                </a>
                            @endif
                            @if((float) $nota->jumlah_diterima > 0)
                                <form action="{{ route('penerimaan-pks.reset', $nota->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Reset penerimaan dana untuk nota {{ $nota->no_nota_timbang }} menjadi 0?')">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">Reset</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">Belum ada data nota timbang PKS.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
