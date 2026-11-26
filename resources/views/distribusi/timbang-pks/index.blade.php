@extends('app')

@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-gleam icon-gradient bg-grow-early"></i>
            </div>
            <div>Hasil Timbang & Nota Penjualan PKS
                <div class="page-title-subheading">Pencatatan nota timbang pabrik (Bruto, Tara, Netto, Rafaksi) dan evaluasi selisih berat</div>
            </div>
        </div>
        <div class="page-title-actions d-flex gap-2">
            <a href="{{ route('pengiriman-tbs.index') }}" class="btn btn-outline-primary btn-shadow">
                <i class="fa fa-truck me-1"></i> Daftar Pengiriman PKS
            </a>
            <a href="{{ route('hasil-timbang-pks.create') }}" class="btn btn-success btn-shadow">
                <i class="fa fa-plus me-1"></i> Input Nota Timbang PKS
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

@if($pengirimanDikirim->isNotEmpty())
<div class="alert alert-warning shadow-sm border-0 d-flex justify-content-between align-items-center">
    <div>
        <i class="fa fa-info-circle me-1"></i>
        Terdapat <strong>{{ $pengirimanDikirim->count() }} pengiriman</strong> berstatus <span class="badge bg-warning text-dark">Dikirim</span> yang menunggu input nota timbang PKS.
    </div>
    <a href="{{ route('hasil-timbang-pks.create') }}" class="btn btn-sm btn-dark">Input Sekarang</a>
</div>
@endif

<div class="main-card mb-3 card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="align-middle mb-0 table table-borderless table-striped table-hover">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>No. Nota Timbang</th>
                        <th>Kode Pengiriman & PKS</th>
                        <th class="text-end">Total Kirim (kg)</th>
                        <th class="text-end">Bruto / Tara (kg)</th>
                        <th class="text-end">Netto (kg)</th>
                        <th class="text-end">Rafaksi (kg)</th>
                        <th class="text-end">Berat Dibayar (kg)</th>
                        <th class="text-center">Indikator Selisih Berat</th>
                        <th class="text-end">Harga Jual</th>
                        <th class="text-end">Nilai Penjualan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hasilTimbangList as $item)
                    @php
                        $totalKirim = (float) ($item->pengiriman?->detailPengiriman?->sum('berat_dimuat') ?? 0);
                        $beratDibayar = (float) $item->berat_dibayar;
                        $selisih = round($beratDibayar - $totalKirim, 2);
                    @endphp
                    <tr>
                        <td class="text-center text-muted">{{ $loop->iteration }}</td>
                        <td class="fw-bold text-primary">{{ $item->no_nota_timbang }}</td>
                        <td>
                            <div class="fw-semibold">
                                @if($item->pengiriman)
                                    <a href="{{ route('pengiriman-tbs.show', $item->pengiriman_id) }}" class="text-decoration-none">
                                        {{ $item->pengiriman->no_surat_jalan }}
                                    </a>
                                @else
                                    -
                                @endif
                            </div>
                            <div class="small text-muted">
                                {{ $item->pengiriman?->pks?->nama ?? '-' }}
                                ({{ $item->pengiriman?->tanggal_kirim?->format('d/m/Y') ?? '-' }})
                            </div>
                        </td>
                        <td class="text-end fw-semibold">{{ number_format($totalKirim, 2, ',', '.') }}</td>
                        <td class="text-end small">
                            <div>B: {{ number_format($item->bruto, 2, ',', '.') }}</div>
                            <div class="text-muted">T: {{ number_format($item->tara, 2, ',', '.') }}</div>
                        </td>
                        <td class="text-end">{{ number_format($item->netto, 2, ',', '.') }}</td>
                        <td class="text-end text-danger">{{ number_format($item->rafaksi, 2, ',', '.') }}</td>
                        <td class="text-end fw-bold">{{ number_format($item->berat_dibayar, 2, ',', '.') }}</td>
                        <td class="text-center">
                            @if($selisih < 0)
                                <span class="badge bg-danger">
                                    Susut {{ number_format($selisih, 2, ',', '.') }} kg
                                </span>
                            @elseif($selisih > 0)
                                <span class="badge bg-success">
                                    +{{ number_format($selisih, 2, ',', '.') }} kg
                                </span>
                            @else
                                <span class="badge bg-secondary">0,00 kg (Sesuai)</span>
                            @endif
                        </td>
                        <td class="text-end">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                        <td class="text-end fw-bold text-success">Rp {{ number_format($item->nilai_penjualan, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <a href="{{ route('hasil-timbang-pks.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('hasil-timbang-pks.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus nota timbang {{ $item->no_nota_timbang }} ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="12" class="text-center text-muted py-4">Belum ada data nota hasil timbang PKS.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
