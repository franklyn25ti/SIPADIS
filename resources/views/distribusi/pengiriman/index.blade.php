@extends('app')

@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-car icon-gradient bg-grow-early"></i>
            </div>
            <div>Pengiriman TBS ke PKS
                <div class="page-title-subheading">Pencatatan pengiriman muatan TBS multi-petani dan pemantauan distribusi ke pabrik</div>
            </div>
        </div>
        <div class="page-title-actions d-flex gap-2">
            <a href="{{ route('hasil-timbang-pks.index') }}" class="btn btn-primary btn-shadow">
                <i class="fa fa-balance-scale me-1"></i> Nota Timbang PKS
            </a>
            <a href="{{ route('pengiriman-tbs.create') }}" class="btn btn-success btn-shadow">
                <i class="fa fa-plus me-1"></i> Catat Pengiriman Baru
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
        <form action="{{ route('pengiriman-tbs.index') }}" method="GET" class="row g-2 mb-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label small text-muted mb-1">PKS Tujuan</label>
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
                <label class="form-label small text-muted mb-1">Status Pengiriman</label>
                <select name="status_pengiriman" class="form-select">
                    <option value="">-- Semua Status --</option>
                    <option value="dikirim" {{ request('status_pengiriman') === 'dikirim' ? 'selected' : '' }}>Dikirim (Menunggu Timbang)</option>
                    <option value="selesai_timbang" {{ request('status_pengiriman') === 'selesai_timbang' ? 'selected' : '' }}>Selesai Timbang PKS</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-filter me-1"></i> Filter
                </button>
                @if(request()->anyFilled(['pks_id', 'status_pengiriman']))
                    <a href="{{ route('pengiriman-tbs.index') }}" class="btn btn-outline-secondary">Reset</a>
                @endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="align-middle mb-0 table table-borderless table-striped table-hover">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>Kode Pengiriman</th>
                        <th>Tanggal Kirim</th>
                        <th>PKS Tujuan</th>
                        <th>Armada & Sopir</th>
                        <th>Daftar Petani (Muatan)</th>
                        <th class="text-end">Total Dimuat (kg)</th>
                        <th class="text-end">Berat Dibayar PKS</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengirimanList as $item)
                    @php
                        $totalDimuat = (float) $item->detailPengiriman->sum('berat_dimuat');
                        $beratDibayar = $item->hasilTimbangPks ? (float) $item->hasilTimbangPks->berat_dibayar : null;
                        $selisih = $beratDibayar !== null ? ($beratDibayar - $totalDimuat) : null;
                    @endphp
                    <tr>
                        <td class="text-center text-muted">{{ $loop->iteration }}</td>
                        <td class="fw-bold text-primary">
                            <a href="{{ route('pengiriman-tbs.show', $item->id) }}" class="text-decoration-none">
                                {{ $item->no_surat_jalan }}
                            </a>
                        </td>
                        <td>{{ $item->tanggal_kirim->format('d/m/Y') }}</td>
                        <td class="fw-semibold">{{ $item->pks->nama ?? '-' }}</td>
                        <td>
                            <div>{{ $item->kendaraan }}</div>
                            <div class="small text-muted">Sopir: {{ $item->sopir }}</div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ $item->detailPengiriman->count() }} Pasokan
                            </span>
                            <div class="small text-muted mt-1">
                                {{ $item->detailPengiriman->map(fn($d) => $d->pasokan?->petani?->nama_petani)->filter()->unique()->implode(', ') }}
                            </div>
                        </td>
                        <td class="text-end fw-bold">{{ number_format($totalDimuat, 2, ',', '.') }} kg</td>
                        <td class="text-end">
                            @if($item->hasilTimbangPks)
                                <div class="fw-bold text-success">{{ number_format($beratDibayar, 2, ',', '.') }} kg</div>
                                <div class="small {{ $selisih < 0 ? 'text-danger' : 'text-success' }}">
                                    Selisih: {{ $selisih >= 0 ? '+' : '' }}{{ number_format($selisih, 2, ',', '.') }} kg
                                </div>
                            @else
                                <span class="text-muted small">Belum timbang</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($item->status_pengiriman === 'selesai_timbang')
                                <span class="badge bg-success">Selesai Timbang</span>
                            @else
                                <span class="badge bg-warning text-dark">Dikirim</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('pengiriman-tbs.show', $item->id) }}" class="btn btn-info btn-sm" title="Lihat Rincian Muatan">
                                <i class="fa fa-eye"></i> Detail
                            </a>
                            @if($item->status_pengiriman === 'dikirim')
                                <a href="{{ route('hasil-timbang-pks.create', ['pengiriman_id' => $item->id]) }}" class="btn btn-success btn-sm">
                                    Input Timbang PKS
                                </a>
                            @endif
                            <form action="{{ route('pengiriman-tbs.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data pengiriman {{ $item->no_surat_jalan }} ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">Belum ada data pengiriman TBS ke PKS.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
