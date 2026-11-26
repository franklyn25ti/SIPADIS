@extends('app')

@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-cash icon-gradient bg-grow-early"></i>
            </div>
            <div>Penetapan Harga Beli TBS Harian
                <div class="page-title-subheading">Pengaturan acuan harga beli Tandan Buah Segar (TBS) dari petani per kilogram (Khusus Pemilik)</div>
            </div>
        </div>
        <div class="page-title-actions">
            <a href="{{ route('harga-beli.create') }}" class="btn btn-success btn-shadow">
                <i class="fa fa-plus me-1"></i> Tetapkan Harga Baru
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

<div class="row mb-3">
    <div class="col-md-5">
        <div class="q-kpi-card q-kpi-dark mb-3" style="height: auto;">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="q-kpi-icon">
                    <i class="fa fa-tags"></i>
                </span>
                <span class="badge bg-warning">Harga Aktif ({{ now()->format('d/m/Y') }})</span>
            </div>
            @if($hargaAktif)
                <div class="display-6 fw-bolder my-2 text-white">
                    Rp {{ number_format($hargaAktif->harga_tbs, 0, ',', '.') }} <span class="fs-5 fw-normal text-warning">/ {{ $hargaAktif->satuan }}</span>
                </div>
                <div class="small text-secondary">
                    Berlaku sejak: <strong class="text-white">{{ $hargaAktif->tanggal_berlaku->format('d/m/Y') }}</strong>
                    @if($hargaAktif->keterangan)
                        <br>Catatan: {{ $hargaAktif->keterangan }}
                    @endif
                </div>
            @else
                <div class="fs-4 fw-bold my-2 text-white">Belum Ditetapkan</div>
                <div class="small text-secondary">Silakan input penetapan harga beli harian pada form di bawah.</div>
            @endif
        </div>

        <div class="main-card mb-3 card shadow-sm border-0">
            <div class="card-header">Form Cepat Penetapan Harga Harian</div>
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

                <form action="{{ route('harga-beli.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Tanggal Berlaku <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_berlaku" class="form-control" value="{{ old('tanggal_berlaku', now()->toDateString()) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga Beli TBS (Rp/kg) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" step="0.01" min="0" name="harga_tbs" class="form-control" value="{{ old('harga_tbs') }}" placeholder="Contoh: 2450" required>
                            <span class="input-group-text">/ kg</span>
                        </div>
                    </div>
                    <input type="hidden" name="satuan" value="kg">
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="2" placeholder="Catatan penyesuaian harga pabrik...">{{ old('keterangan') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fa fa-check me-1"></i> Simpan Harga Berlaku
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="main-card mb-3 card shadow-sm border-0">
            <div class="card-header">Riwayat Perubahan Harga Beli TBS</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="align-middle mb-0 table table-borderless table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>Tanggal Berlaku</th>
                                <th class="text-end">Harga TBS (Rp/kg)</th>
                                <th>Keterangan</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($hargaList as $item)
                            <tr>
                                <td class="text-center text-muted">{{ $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $item->tanggal_berlaku->format('d/m/Y') }}</td>
                                <td class="text-end fw-bold text-success">
                                    Rp {{ number_format($item->harga_tbs, 0, ',', '.') }} / {{ $item->satuan }}
                                </td>
                                <td>{{ $item->keterangan ?? '-' }}</td>
                                <td class="text-center">
                                    @if($hargaAktif && $hargaAktif->id === $item->id)
                                        <span class="badge bg-success">Aktif Digunakan</span>
                                    @else
                                        <span class="badge bg-secondary">Riwayat</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('harga-beli.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('harga-beli.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus riwayat harga beli ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada riwayat penetapan harga beli TBS.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
