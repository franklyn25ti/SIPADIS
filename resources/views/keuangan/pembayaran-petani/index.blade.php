@extends('app')

@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-cash icon-gradient bg-grow-early"></i>
            </div>
            <div>Pembayaran & Pelunasan Petani
                <div class="page-title-subheading">Pantau tagihan pasokan TBS petani yang belum lunas dan catat pembayaran bertahap atau tunai</div>
            </div>
        </div>
        <div class="page-title-actions">
            <a href="{{ route('pembayaran-petani.create') }}" class="btn btn-success btn-shadow">
                <i class="fa fa-plus me-1"></i> Catat Pembayaran Petani
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
                <span class="q-kpi-icon"><i class="fa fa-file-invoice-dollar"></i></span>
                <span class="badge bg-warning">Hutang Petani</span>
            </div>
            <div class="q-kpi-label">Total Sisa Tagihan Petani</div>
            <div class="q-kpi-value">Rp {{ number_format($totalTagihanBelumLunas, 0, ',', '.') }}</div>
            <div class="q-kpi-trend text-warning">
                <i class="fa fa-clock"></i>
                <span>{{ $jumlahTagihanAktif }} transaksi menunggu pelunasan</span>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="q-kpi-card q-kpi-light">
            <div class="d-flex justify-content-between align-items-start">
                <span class="q-kpi-icon q-icon-amber"><i class="fa fa-list-alt"></i></span>
            </div>
            <div class="q-kpi-label">Jumlah Pasokan Menunggu Bayar</div>
            <div class="q-kpi-value">{{ number_format($jumlahTagihanAktif, 0, ',', '.') }}</div>
            <div class="q-kpi-trend text-muted">
                <span>Status Belum Dibayar / Sebagian</span>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="q-kpi-card q-kpi-light">
            <div class="d-flex justify-content-between align-items-start">
                <span class="q-kpi-icon"><i class="fa fa-check-double"></i></span>
            </div>
            <div class="q-kpi-label">Total Sudah Dibayarkan</div>
            <div class="q-kpi-value text-success">Rp {{ number_format($totalSudahDibayar, 0, ',', '.') }}</div>
            <div class="q-kpi-trend text-success">
                <i class="fa fa-check-circle"></i>
                <span>Akumulasi pembayaran tercatat</span>
            </div>
        </div>
    </div>
</div>

<!-- Card 1: Daftar Tagihan Pasokan Belum Lunas -->
<div class="main-card mb-4 card shadow-sm border-0">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Daftar Tagihan Pasokan Belum Lunas</span>
        <span class="badge bg-warning text-dark">{{ $jumlahTagihanAktif }} Tagihan Aktif</span>
    </div>
    <div class="card-body">
        <form action="{{ route('pembayaran-petani.index') }}" method="GET" class="row g-2 mb-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label small text-muted mb-1">Filter Petani</label>
                <select name="petani_id" class="form-select">
                    <option value="">-- Semua Petani --</option>
                    @foreach($petaniList as $petani)
                        <option value="{{ $petani->id }}" {{ (string) request('petani_id') === (string) $petani->id ? 'selected' : '' }}>
                            {{ $petani->nama_petani }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted mb-1">Status Pembayaran</label>
                <select name="status_pembayaran" class="form-select">
                    <option value="">-- Semua Status Belum Lunas --</option>
                    <option value="belum_dibayar" {{ request('status_pembayaran') === 'belum_dibayar' ? 'selected' : '' }}>Belum Dibayar</option>
                    <option value="dibayar_sebagian" {{ request('status_pembayaran') === 'dibayar_sebagian' ? 'selected' : '' }}>Dibayar Sebagian</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-filter me-1"></i> Filter
                </button>
                @if(request()->anyFilled(['petani_id', 'status_pembayaran']))
                    <a href="{{ route('pembayaran-petani.index') }}" class="btn btn-outline-secondary">Reset</a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="align-middle mb-0 table table-borderless table-striped table-hover">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>No. Transaksi</th>
                        <th>Tanggal</th>
                        <th>Petani Mitra</th>
                        <th class="text-end">Netto (kg)</th>
                        <th class="text-end">Nilai Pembelian</th>
                        <th class="text-end">Sudah Dibayar</th>
                        <th class="text-end">Sisa Tagihan</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tagihanList as $item)
                    <tr>
                        <td class="text-center text-muted">{{ $loop->iteration }}</td>
                        <td class="fw-bold text-primary">{{ $item->no_transaksi }}</td>
                        <td>{{ $item->tanggal->format('d/m/Y') }}</td>
                        <td class="fw-semibold">{{ $item->petani->nama_petani ?? '-' }}</td>
                        <td class="text-end">{{ number_format($item->berat_netto, 2, ',', '.') }} kg</td>
                        <td class="text-end">Rp {{ number_format($item->nilai_pembelian, 0, ',', '.') }}</td>
                        <td class="text-end text-success">Rp {{ number_format($item->total_dibayar, 0, ',', '.') }}</td>
                        <td class="text-end fw-bold text-danger">Rp {{ number_format($item->sisa_tagihan, 0, ',', '.') }}</td>
                        <td class="text-center">
                            @if($item->status_pembayaran === 'dibayar_sebagian')
                                <span class="badge bg-warning text-dark">Dibayar Sebagian</span>
                            @else
                                <span class="badge bg-danger">Belum Dibayar</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('pembayaran-petani.create', ['pasokan_id' => $item->id]) }}" class="btn btn-success btn-sm">
                                <i class="fa fa-money-bill-wave me-1"></i> Bayar
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">Seluruh tagihan pasokan petani sudah lunas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Card 2: Riwayat Pembayaran Petani -->
<div class="main-card mb-3 card shadow-sm border-0">
    <div class="card-header">Riwayat Pembayaran Petani</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="align-middle mb-0 table table-borderless table-striped table-hover">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>Tgl Bayar</th>
                        <th>No. Transaksi</th>
                        <th>Petani</th>
                        <th>Metode</th>
                        <th class="text-end">Jumlah Bayar</th>
                        <th>Keterangan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayatPembayaran as $bayar)
                    <tr>
                        <td class="text-center text-muted">{{ $loop->iteration }}</td>
                        <td>{{ $bayar->tanggal_bayar->format('d/m/Y') }}</td>
                        <td class="fw-bold text-primary">{{ $bayar->pasokan->no_transaksi ?? '-' }}</td>
                        <td class="fw-semibold">{{ $bayar->pasokan?->petani?->nama_petani ?? '-' }}</td>
                        <td><span class="badge bg-info">{{ $bayar->metode_pembayaran }}</span></td>
                        <td class="text-end fw-bold text-success">Rp {{ number_format($bayar->jumlah_bayar, 0, ',', '.') }}</td>
                        <td class="small text-muted">{{ $bayar->keterangan ?? '-' }}</td>
                        <td class="text-center">
                            <form action="{{ route('pembayaran-petani.destroy', $bayar->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus riwayat pembayaran ini? Status tagihan pasokan akan otomatis disesuaikan ulang.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">Belum ada riwayat pembayaran petani yang tercatat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
