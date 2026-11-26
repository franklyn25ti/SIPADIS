@extends('app')

@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-culture icon-gradient bg-grow-early"></i>
            </div>
            <div>Data PKS / Pabrik Pembeli
                <div class="page-title-subheading">Daftar Pabrik Kelapa Sawit (PKS) tujuan pengiriman dan penjualan TBS</div>
            </div>
        </div>
        <div class="page-title-actions">
            <a href="{{ route('pks-pembeli.create') }}" class="btn btn-success btn-shadow">
                <i class="fa fa-plus me-1"></i> Tambah PKS
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
        <form action="{{ route('pks-pembeli.index') }}" method="GET" class="row g-2 mb-3 align-items-end">
            <div class="col-md-6">
                <label class="form-label small text-muted mb-1">Cari Nama PKS / Alamat / Kontak</label>
                <input type="text" name="search" class="form-control" placeholder="Ketik nama pabrik atau alamat..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-search me-1"></i> Cari
                </button>
                @if(request()->filled('search'))
                    <a href="{{ route('pks-pembeli.index') }}" class="btn btn-outline-secondary">Reset</a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="align-middle mb-0 table table-borderless table-striped table-hover">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>Nama PKS / Pabrik</th>
                        <th>No. Kontak</th>
                        <th>Alamat Pabrik</th>
                        <th>Keterangan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pksList as $item)
                    <tr>
                        <td class="text-center text-muted">{{ $loop->iteration }}</td>
                        <td class="fw-bold">{{ $item->nama }}</td>
                        <td>{{ $item->no_kontak ?? '-' }}</td>
                        <td>{{ $item->alamat ?? '-' }}</td>
                        <td>{{ $item->keterangan ?? '-' }}</td>
                        <td class="text-center">
                            <a href="{{ route('pks-pembeli.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('pks-pembeli.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data PKS ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Belum ada data PKS / Pembeli terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
