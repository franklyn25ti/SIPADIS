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
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="main-card mb-3 card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="align-middle mb-0 table table-borderless table-striped table-hover">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>Nama Petani</th>
                        <th>No. HP</th>
                        <th>Alamat / Lokasi Kebun</th>
                        <th class="text-center">Luas Lahan (Ha)</th>
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
                        <td colspan="6" class="text-center text-muted py-4">Belum ada data petani.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection