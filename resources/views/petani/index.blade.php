@extends('app')

@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-users icon-gradient bg-grow-early"></i>
            </div>
            <div>Data Petani Sawit
                <div class="page-title-subheading">Daftar pemasok Tandan Buah Segar (TBS) terdaftar</div>
            </div>
        </div>
        <div class="page-title-actions">
            <a href="{{ route('petani.create') }}" class="btn btn-success btn-shadow">
                <i class="fa fa-plus me-1"></i> Tambah Petani
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
        <form action="{{ route('petani.index') }}" method="GET" class="row g-2 mb-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label small text-muted mb-1">Cari Nama / Alamat / No. HP</label>
                <input type="text" name="search" class="form-control" placeholder="Ketik nama petani atau alamat kebun..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted mb-1">Status Mitra</label>
                <select name="status_aktif" class="form-select">
                    <option value="">-- Semua Status --</option>
                    <option value="1" {{ request('status_aktif') === '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ request('status_aktif') === '0' ? 'selected' : '' }}>Non-Aktif</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-search me-1"></i> Filter
                </button>
                @if(request()->filled('search') || request()->filled('status_aktif'))
                    <a href="{{ route('petani.index') }}" class="btn btn-outline-secondary">Reset</a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="align-middle mb-0 table table-borderless table-striped table-hover">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>Nama Petani</th>
                        <th>No. HP</th>
                        <th>Alamat / Lokasi Kebun</th>
                        <th class="text-center">Luas Lahan (Ha)</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($petanis as $item)
                    <tr>
                        <td class="text-center text-muted">{{ $loop->iteration }}</td>
                        <td class="fw-bold">{{ $item->nama_petani }}</td>
                        <td>{{ $item->no_hp ?? '-' }}</td>
                        <td>{{ $item->alamat ?? '-' }}</td>
                        <td class="text-center">{{ number_format($item->luas_lahan, 2) }} Ha</td>
                        <td class="text-center">
                            @if($item->status_aktif)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Non-Aktif</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('petani.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('petani.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data petani ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Belum ada data petani.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
