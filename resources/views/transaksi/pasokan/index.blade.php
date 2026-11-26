@extends('app')

@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-box2 icon-gradient bg-grow-early"></i>
            </div>
            <div>Pencatatan Pasokan TBS Petani
                <div class="page-title-subheading">Daftar pencatatan pasokan Tandan Buah Segar (TBS) dari petani mitra</div>
            </div>
        </div>
        <div class="page-title-actions">
            <a href="{{ route('pasokan-tbs.create') }}" class="btn btn-success btn-shadow">
                <i class="fa fa-plus me-1"></i> Catat Pasokan TBS
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

<div class="main-card mb-3 card shadow-sm border-0">
    <div class="card-body">
        <form action="{{ route('pasokan-tbs.index') }}" method="GET" class="row g-2 mb-3 align-items-end">
            <div class="col-md-2">
                <label class="form-label small text-muted mb-1">Dari Tanggal</label>
                <input type="date" name="tanggal_mulai" class="form-control" value="{{ request('tanggal_mulai') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small text-muted mb-1">Sampai Tanggal</label>
                <input type="date" name="tanggal_selesai" class="form-control" value="{{ request('tanggal_selesai') }}">
            </div>
            <div class="col-md-3">
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
            <div class="col-md-2">
                <label class="form-label small text-muted mb-1">Status Bayar</label>
                <select name="status_pembayaran" class="form-select">
                    <option value="">-- Semua Status --</option>
                    <option value="belum_dibayar" {{ request('status_pembayaran') === 'belum_dibayar' ? 'selected' : '' }}>Belum Dibayar</option>
                    <option value="dibayar_sebagian" {{ request('status_pembayaran') === 'dibayar_sebagian' ? 'selected' : '' }}>Dibayar Sebagian</option>
                    <option value="lunas" {{ request('status_pembayaran') === 'lunas' ? 'selected' : '' }}>Lunas</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-filter me-1"></i> Filter
                </button>
                @if(request()->anyFilled(['tanggal_mulai', 'tanggal_selesai', 'petani_id', 'status_pembayaran']))
                    <a href="{{ route('pasokan-tbs.index') }}" class="btn btn-outline-secondary">Reset</a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="align-middle mb-0 table table-borderless table-striped table-hover">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>Tanggal</th>
                        <th>Petani Mitra</th>
                        <th class="text-end">Berat Pasokan (kg)</th>
                        <th class="text-end">Harga (Rp/kg)</th>
                        <th class="text-end">Nilai Pembelian</th>
                        <th class="text-center">Status Bayar</th>
                        <th>Petugas</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pasokanList as $item)
                    <tr>
                        <td class="text-center text-muted">{{ $loop->iteration }}</td>
                        <td>{{ $item->tanggal->format('d/m/Y') }}</td>
                        <td class="fw-semibold">{{ $item->petani->nama_petani ?? '-' }}</td>
                        <td class="text-end fw-bold">{{ number_format($item->berat_netto, 2, ',', '.') }}</td>
                        <td class="text-end">Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                        <td class="text-end fw-bold text-success">Rp {{ number_format($item->nilai_pembelian, 0, ',', '.') }}</td>
                        <td class="text-center">
                            @if($item->status_pembayaran === 'lunas')
                                <span class="badge bg-success">Lunas</span>
                            @elseif($item->status_pembayaran === 'dibayar_sebagian')
                                <span class="badge bg-warning text-dark">Dibayar Sebagian</span>
                            @else
                                <span class="badge bg-danger">Belum Dibayar</span>
                            @endif
                        </td>
                        <td class="small text-muted">{{ $item->user->name ?? '-' }}</td>
                        <td class="text-center">
                            <a href="{{ route('pasokan-tbs.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('pasokan-tbs.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data pasokan dari {{ $item->petani->nama_petani ?? 'petani' }} ({{ $item->tanggal->format('d/m/Y') }})?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">Belum ada data pasokan TBS dari petani.</td>
                    </tr>
                    @endforelse
                </tbody>
                @if($pasokanList->isNotEmpty())
                <tfoot class="table-light fw-bold">
                    <tr>
                        <td colspan="3" class="text-end">Total:</td>
                        <td class="text-end">{{ number_format($pasokanList->sum('berat_netto'), 2, ',', '.') }} kg</td>
                        <td></td>
                        <td class="text-end text-success">Rp {{ number_format($pasokanList->sum('nilai_pembelian'), 0, ',', '.') }}</td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection
