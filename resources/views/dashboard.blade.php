@extends('app')

@section('content')
@php
    $totalNilaiPenjualan = (float) ($stats['total_nilai_penjualan'] ?? 0);
    $totalNilaiPembelian = (float) ($stats['total_nilai_pembelian'] ?? 0);
    $totalTonasePasokan  = (float) ($stats['total_tonase_pasokan'] ?? 0);
    $jumlahPetaniAktif   = (int) ($stats['jumlah_petani_aktif'] ?? 0);
    $jumlahPengiriman    = (int) ($stats['jumlah_pengiriman'] ?? 0);
    $hargaAktif          = $stats['harga_aktif'] ?? null;
    $topPetani           = $stats['top_petani'] ?? collect();
    $pasokanTerbaru      = $stats['pasokan_terbaru'] ?? collect();
    $distribusiPks       = $stats['distribusi_pks'] ?? collect();

    $lunasCount          = (int) ($stats['count_lunas'] ?? 0);
    $sebagianCount       = (int) ($stats['count_sebagian'] ?? 0);
    $belumCount          = (int) ($stats['count_belum'] ?? 0);
    $totalTransaksiCount = max(1, $lunasCount + $sebagianCount + $belumCount);

    $pctBelum    = round(($belumCount / $totalTransaksiCount) * 100);
    $pctSebagian = round(($sebagianCount / $totalTransaksiCount) * 100);
    $pctLunas    = max(0, 100 - $pctBelum - $pctSebagian);
@endphp

<!-- Top Action Row -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0 text-dark">Ringkasan Operasional Hari Ini</h5>
        <small class="text-muted">Statistik real-time timbangan TBS, mitra petani, dan pengiriman pabrik</small>
    </div>
    <div class="mt-2 mt-sm-0 d-flex gap-2">
        <a href="{{ route('pengiriman-tbs.create') }}" class="btn btn-outline-dark px-3">
            <i class="fa fa-truck me-1"></i> Catat Pengiriman PKS
        </a>
        <a href="{{ route('pasokan-tbs.create') }}" class="btn btn-q-dark">
            <i class="fa fa-plus me-1"></i> Catat Pasokan TBS
        </a>
    </div>
</div>

<!-- Row 1: 4 KPI Cards (Matches Quantro Reference 1 Dark Card + 3 Light Cards with Sparklines) -->
<div class="row g-3 mb-4">
    <!-- Card 1: Featured Dark Card -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="q-kpi-card q-kpi-dark">
            <div class="d-flex justify-content-between align-items-start">
                <div class="q-kpi-icon">
                    <i class="fa fa-chart-bar"></i>
                </div>
                <a href="{{ route('hasil-timbang-pks.index') }}" class="text-secondary text-decoration-none">
                    <i class="fa fa-ellipsis-v"></i>
                </a>
            </div>
            <div class="d-flex justify-content-between align-items-end mt-2">
                <div>
                    <div class="q-kpi-label">Total Pembelian TBS</div>
                    <div class="q-kpi-value">Rp {{ number_format($totalNilaiPembelian, 0, ',', '.') }}</div>
                    <div class="q-kpi-trend text-success">
                        <i class="fa fa-arrow-up"></i>
                        <span>Rp {{ number_format($hargaAktif?->harga_tbs ?? 0, 0, ',', '.') }}/kg</span>
                        <span class="text-secondary fw-normal">harga aktif</span>
                    </div>
                </div>
                <svg width="82" height="44" viewBox="0 0 82 44" fill="none" class="ms-2 flex-shrink-0">
                    <defs>
                        <linearGradient id="gradDark" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#f59e0b" stop-opacity="0.45"/>
                            <stop offset="100%" stop-color="#f59e0b" stop-opacity="0.0"/>
                        </linearGradient>
                    </defs>
                    <path d="M2 34 C14 34, 18 18, 28 24 C38 30, 44 12, 54 18 C64 24, 72 6, 80 10 L80 42 L2 42 Z" fill="url(#gradDark)"/>
                    <path d="M2 34 C14 34, 18 18, 28 24 C38 30, 44 12, 54 18 C64 24, 72 6, 80 10" stroke="#fbbf24" stroke-width="2.5" stroke-linecap="round"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Card 2: Total Pasokan Tonase -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="q-kpi-card q-kpi-light">
            <div class="d-flex justify-content-between align-items-start">
                <div class="q-kpi-icon">
                    <i class="fa fa-shopping-cart"></i>
                </div>
                <a href="{{ route('pasokan-tbs.index') }}" class="text-muted text-decoration-none">
                    <i class="fa fa-ellipsis-v"></i>
                </a>
            </div>
            <div class="d-flex justify-content-between align-items-end mt-2">
                <div>
                    <div class="q-kpi-label">Total Tonase Pasokan</div>
                    <div class="q-kpi-value">{{ number_format($totalTonasePasokan, 0, ',', '.') }} <span class="fs-6 fw-bold text-muted">kg</span></div>
                    <div class="q-kpi-trend text-success">
                        <i class="fa fa-arrow-up"></i>
                        <span>{{ $totalTransaksiCount }} transaksi</span>
                        <span class="text-muted fw-normal">tercatat</span>
                    </div>
                </div>
                <svg width="82" height="44" viewBox="0 0 82 44" fill="none" class="ms-2 flex-shrink-0">
                    <defs>
                        <linearGradient id="gradLight1" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#f97316" stop-opacity="0.3"/>
                            <stop offset="100%" stop-color="#f97316" stop-opacity="0.0"/>
                        </linearGradient>
                    </defs>
                    <path d="M2 36 C16 32, 22 20, 32 26 C42 32, 50 14, 62 20 C70 24, 74 8, 80 12 L80 42 L2 42 Z" fill="url(#gradLight1)"/>
                    <path d="M2 36 C16 32, 22 20, 32 26 C42 32, 50 14, 62 20 C70 24, 74 8, 80 12" stroke="#f97316" stroke-width="2.2" stroke-linecap="round"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Card 3: Petani Mitra Aktif -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="q-kpi-card q-kpi-light">
            <div class="d-flex justify-content-between align-items-start">
                <div class="q-kpi-icon q-icon-amber">
                    <i class="fa fa-users"></i>
                </div>
                <a href="{{ route('petani.index') }}" class="text-muted text-decoration-none">
                    <i class="fa fa-ellipsis-v"></i>
                </a>
            </div>
            <div class="d-flex justify-content-between align-items-end mt-2">
                <div>
                    <div class="q-kpi-label">Petani Mitra Aktif</div>
                    <div class="q-kpi-value">{{ number_format($jumlahPetaniAktif, 0, ',', '.') }}</div>
                    <div class="q-kpi-trend text-success">
                        <i class="fa fa-check-circle"></i>
                        <span>Mitra Terdaftar</span>
                        <span class="text-muted fw-normal">siap pasok</span>
                    </div>
                </div>
                <svg width="82" height="44" viewBox="0 0 82 44" fill="none" class="ms-2 flex-shrink-0">
                    <defs>
                        <linearGradient id="gradLight2" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#f59e0b" stop-opacity="0.3"/>
                            <stop offset="100%" stop-color="#f59e0b" stop-opacity="0.0"/>
                        </linearGradient>
                    </defs>
                    <path d="M2 34 C14 36, 24 22, 34 26 C44 30, 52 14, 62 18 C72 22, 76 8, 80 10 L80 42 L2 42 Z" fill="url(#gradLight2)"/>
                    <path d="M2 34 C14 36, 24 22, 34 26 C44 30, 52 14, 62 18 C72 22, 76 8, 80 10" stroke="#f59e0b" stroke-width="2.2" stroke-linecap="round"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Card 4: Penjualan / Distribusi PKS -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="q-kpi-card q-kpi-light">
            <div class="d-flex justify-content-between align-items-start">
                <div class="q-kpi-icon">
                    <i class="fa fa-location-arrow"></i>
                </div>
                <a href="{{ route('pengiriman-tbs.index') }}" class="text-muted text-decoration-none">
                    <i class="fa fa-ellipsis-v"></i>
                </a>
            </div>
            <div class="d-flex justify-content-between align-items-end mt-2">
                <div>
                    <div class="q-kpi-label">Penjualan ke PKS</div>
                    <div class="q-kpi-value">Rp {{ number_format($totalNilaiPenjualan, 0, ',', '.') }}</div>
                    <div class="q-kpi-trend text-success">
                        <i class="fa fa-truck"></i>
                        <span>{{ $jumlahPengiriman }} Pengiriman</span>
                        <span class="text-muted fw-normal">tercatat</span>
                    </div>
                </div>
                <svg width="82" height="44" viewBox="0 0 82 44" fill="none" class="ms-2 flex-shrink-0">
                    <path d="M2 35 C15 30, 22 18, 32 24 C42 30, 50 12, 62 18 C72 23, 76 9, 80 12 L80 42 L2 42 Z" fill="url(#gradLight1)"/>
                    <path d="M2 35 C15 30, 22 18, 32 24 C42 30, 50 12, 62 18 C72 23, 76 9, 80 12" stroke="#ea580c" stroke-width="2.2" stroke-linecap="round"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Row 2: Revenue/Pasokan Overview Chart + Donut Breakdown (Matches Middle Section of Quantro) -->
<div class="row g-3 mb-4">
    <div class="col-12 col-lg-8">
        <div class="q-card p-4 h-100">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="q-kpi-icon q-icon-amber" style="width: 36px; height: 36px;">
                        <i class="fa fa-chart-line"></i>
                    </span>
                    <div>
                        <h6 class="fw-bold mb-0">Tren Pasokan & Pembelian TBS</h6>
                        <small class="text-muted">Ikhtisar volume timbangan masuk dan nilai transaksi bulan berjalan</small>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 mt-2 mt-sm-0">
                    <div class="p-1 rounded-pill d-inline-flex" style="background-color: #e5e7eb;">
                        <span class="badge bg-dark text-white rounded-pill px-3 py-1">Pembelian</span>
                        <a href="{{ route('hasil-timbang-pks.index') }}" class="text-muted text-decoration-none small fw-semibold px-3 py-1">Penjualan PKS</a>
                    </div>
                </div>
            </div>

            <!-- Smooth Area SVG Chart matching Quantro's Amber Curve -->
            <div class="position-relative pt-2">
                <svg viewBox="0 0 700 210" class="w-100" style="height: 210px;">
                    <defs>
                        <linearGradient id="qMainArea" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#f59e0b" stop-opacity="0.32"/>
                            <stop offset="100%" stop-color="#f59e0b" stop-opacity="0.0"/>
                        </linearGradient>
                    </defs>
                    <!-- Horizontal Grid Lines -->
                    <line x1="45" y1="30" x2="680" y2="30" stroke="#e5e7eb" stroke-dasharray="4 4"/>
                    <line x1="45" y1="75" x2="680" y2="75" stroke="#e5e7eb" stroke-dasharray="4 4"/>
                    <line x1="45" y1="120" x2="680" y2="120" stroke="#e5e7eb" stroke-dasharray="4 4"/>
                    <line x1="45" y1="165" x2="680" y2="165" stroke="#e5e7eb"/>

                    <!-- Y Axis Labels -->
                    <text x="8" y="34" fill="#9ca3af" font-size="11">20T</text>
                    <text x="8" y="79" fill="#9ca3af" font-size="11">15T</text>
                    <text x="8" y="124" fill="#9ca3af" font-size="11">10T</text>
                    <text x="14" y="169" fill="#9ca3af" font-size="11">0 kg</text>

                    <!-- Smooth Curve Area & Stroke -->
                    <path d="M45 155 C95 150, 115 110, 155 125 C195 140, 225 85, 275 105 C325 125, 365 135, 415 95 C465 55, 495 55, 535 90 C575 125, 625 65, 680 35 L680 165 L45 165 Z" fill="url(#qMainArea)"/>
                    <path d="M45 155 C95 150, 115 110, 155 125 C195 140, 225 85, 275 105 C325 125, 365 135, 415 95 C465 55, 495 55, 535 90 C575 125, 625 65, 680 35" fill="none" stroke="#f59e0b" stroke-width="3" stroke-linecap="round"/>

                    <!-- Highlighted Point + Dark Tooltip Pill -->
                    <line x1="485" y1="64" x2="485" y2="165" stroke="#d1d5db" stroke-dasharray="3 3"/>
                    <circle cx="485" cy="64" r="6" fill="#f59e0b" stroke="#ffffff" stroke-width="2.5"/>
                    <rect x="430" y="22" width="110" height="30" rx="8" fill="#18191e"/>
                    <text x="485" y="41" text-anchor="middle" fill="#ffffff" font-size="11" font-weight="700">
                        {{ number_format($totalTonasePasokan, 0, ',', '.') }} kg
                    </text>

                    <!-- X Axis Labels -->
                    <text x="45" y="190" fill="#9ca3af" font-size="11">Minggu 1</text>
                    <text x="195" y="190" fill="#9ca3af" font-size="11">Minggu 2</text>
                    <text x="355" y="190" fill="#9ca3af" font-size="11">Minggu 3</text>
                    <text x="505" y="190" fill="#9ca3af" font-size="11">Minggu 4</text>
                    <text x="635" y="190" fill="#9ca3af" font-size="11">Hari Ini</text>
                </svg>
            </div>
        </div>
    </div>

    <!-- Right 4 Cols: Donut Chart Card ("Sales by Category" style) -->
    <div class="col-12 col-lg-4">
        <div class="q-card p-4 h-100 d-flex flex-column justify-content-between">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="q-kpi-icon" style="width: 34px; height: 34px; background:#18191e; color:#fff;">
                        <i class="fa fa-chart-pie"></i>
                    </span>
                    <h6 class="fw-bold mb-0">Status Pembayaran</h6>
                </div>
                <span class="badge bg-white text-muted border">Bulan Ini</span>
            </div>

            <div class="row align-items-center my-auto">
                <div class="col-6 text-center position-relative">
                    <svg viewBox="0 0 36 36" class="w-100" style="max-width: 145px; transform: rotate(-90deg);">
                        <circle cx="18" cy="18" r="14" fill="none" stroke="#e5e7eb" stroke-width="5"></circle>
                        <circle cx="18" cy="18" r="14" fill="none" stroke="#f59e0b" stroke-width="5"
                                stroke-dasharray="{{ $pctBelum }} {{ 100 - $pctBelum }}" stroke-dashoffset="0"></circle>
                        <circle cx="18" cy="18" r="14" fill="none" stroke="#fbbf24" stroke-width="5"
                                stroke-dasharray="{{ $pctSebagian }} {{ 100 - $pctSebagian }}" stroke-dashoffset="-{{ $pctBelum }}"></circle>
                        <circle cx="18" cy="18" r="14" fill="none" stroke="#18191e" stroke-width="5"
                                stroke-dasharray="{{ $pctLunas }} {{ 100 - $pctLunas }}" stroke-dashoffset="-{{ $pctBelum + $pctSebagian }}"></circle>
                    </svg>
                    <div class="position-absolute top-50 start-50 translate-middle text-center">
                        <div class="fw-bolder fs-5 text-dark">{{ $stats['total_transaksi'] ?? 0 }}</div>
                        <div class="text-muted" style="font-size: 0.68rem;">Transaksi</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="d-flex justify-content-between align-items-center mb-2 small">
                        <span><i class="fa fa-circle me-1" style="color: #f59e0b;"></i> Belum Bayar</span>
                        <strong>{{ $pctBelum }}%</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2 small">
                        <span><i class="fa fa-circle me-1" style="color: #fbbf24;"></i> Sebagian</span>
                        <strong>{{ $pctSebagian }}%</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2 small">
                        <span><i class="fa fa-circle me-1" style="color: #18191e;"></i> Lunas</span>
                        <strong>{{ $pctLunas }}%</strong>
                    </div>
                </div>
            </div>

            <div class="pt-3 border-top d-flex justify-content-between align-items-center small text-muted">
                <span>Harga TBS Aktif:</span>
                <strong class="text-dark">Rp {{ number_format($hargaAktif?->harga_tbs ?? 0, 0, ',', '.') }} / kg</strong>
            </div>
        </div>
    </div>
</div>

<!-- Row 3: Bottom 3 Panels (Top Petani, Pasokan Terbaru, Distribusi PKS) -->
<div class="row g-3">
    <!-- Left Panel: Top Selling Products style -> Petani Pemasok Teratas -->
    <div class="col-12 col-lg-5">
        <div class="q-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa fa-award text-warning fs-5"></i>
                    <h6 class="fw-bold mb-0">Petani Pemasok Teratas</h6>
                </div>
                <a href="{{ route('petani.index') }}" class="text-dark fw-semibold small text-decoration-none">
                    Lihat Semua <i class="fa fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Petani Mitra</th>
                            <th class="text-center">Pasokan</th>
                            <th class="text-end">Tonase (kg)</th>
                            <th class="text-end">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topPetani as $p)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="q-avatar-circle bg-white border text-dark">
                                        <i class="fa fa-leaf text-warning"></i>
                                    </span>
                                    <div>
                                        <div class="fw-bold">{{ $p->nama_petani }}</div>
                                        <div class="small text-muted">{{ number_format($p->luas_lahan, 1) }} Ha</div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center fw-semibold">{{ $p->pasokan_tbs_count }}x</td>
                            <td class="text-end fw-bold">{{ number_format($p->total_netto ?? 0, 0, ',', '.') }}</td>
                            <td class="text-end text-success fw-semibold small">
                                <i class="fa fa-arrow-up"></i> Aktif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4 small">Belum ada data pasokan petani.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Middle Panel: Recent Orders style -> Transaksi Pasokan Terbaru -->
    <div class="col-12 col-lg-4">
        <div class="q-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa fa-receipt text-dark fs-5"></i>
                    <h6 class="fw-bold mb-0">Pasokan TBS Terbaru</h6>
                </div>
                <a href="{{ route('pasokan-tbs.index') }}" class="text-dark fw-semibold small text-decoration-none">
                    Lihat Semua <i class="fa fa-arrow-right ms-1"></i>
                </a>
            </div>

            <div class="d-flex flex-column gap-3">
                @php
                    $avatarColors = [
                        ['bg' => '#fce7f3', 'text' => '#be185d'],
                        ['bg' => '#ede9fe', 'text' => '#6d28d9'],
                        ['bg' => '#ffedd5', 'text' => '#c2410c'],
                        ['bg' => '#dcfce7', 'text' => '#15803d'],
                    ];
                @endphp
                @forelse($pasokanTerbaru as $idx => $psk)
                @php
                    $color = $avatarColors[$idx % count($avatarColors)];
                    $nama = $psk->petani->nama_petani ?? 'Petani';
                    $initials = collect(explode(' ', $nama))->map(fn($w) => strtoupper(substr($w, 0, 1)))->take(2)->implode('');
                @endphp
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <span class="q-avatar-circle" style="background-color: {{ $color['bg'] }}; color: {{ $color['text'] }};">
                            {{ $initials ?: 'PT' }}
                        </span>
                        <div>
                            <div class="fw-bold small text-dark">{{ $nama }}</div>
                            <div class="text-muted" style="font-size: 0.74rem;">#{{ $psk->no_transaksi }}</div>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold small">Rp {{ number_format($psk->nilai_pembelian, 0, ',', '.') }}</div>
                        @if($psk->status_pembayaran === 'lunas')
                            <span class="badge bg-success">Lunas</span>
                        @elseif($psk->status_pembayaran === 'dibayar_sebagian')
                            <span class="badge bg-info">Sebagian</span>
                        @else
                            <span class="badge bg-warning">Belum Bayar</span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-4 small">Belum ada transaksi pasokan terbaru.</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Right Panel: Customers by Location style -> Distribusi Tujuan PKS -->
    <div class="col-12 col-lg-3">
        <div class="q-card p-4 h-100 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa fa-map-marker-alt text-warning fs-5"></i>
                        <h6 class="fw-bold mb-0">Distribusi PKS</h6>
                    </div>
                    <a href="{{ route('pks-pembeli.index') }}" class="small text-muted text-decoration-none">PKS &rarr;</a>
                </div>

                @forelse($distribusiPks as $pks)
                <div class="p-3 rounded-4 bg-white border mb-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="fw-bold small text-dark">{{ $pks->nama }}</div>
                        <span class="badge bg-warning">{{ $pks->pengiriman_tbs_count }} Truk</span>
                    </div>
                    <div class="small text-muted mt-1 text-truncate">
                        <i class="fa fa-map-pin me-1"></i> {{ $pks->alamat ?: 'Lokasi PKS Terdaftar' }}
                    </div>
                </div>
                @empty
                <div class="p-3 rounded-4 bg-dark text-white text-center my-3">
                    <div class="fw-bold small">PKS Tujuan Distribusi</div>
                    <div class="small text-secondary mt-1">Tambahkan pabrik pembeli untuk mulai mencatat pengiriman TBS.</div>
                </div>
                @endforelse
            </div>

            <a href="{{ route('pengiriman-tbs.index') }}" class="btn btn-outline-dark w-100 mt-2">
                Kelola Pengiriman PKS <i class="fa fa-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</div>
@endsection
