<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="id">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>@yield('title', 'SIPADIS - Sistem Informasi Pengepul Kelapa Sawit')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="Sistem Informasi Pencatatan Pasokan Petani, Distribusi, dan Keuangan Pengepul Kelapa Sawit Berbasis Web.">
    <meta name="msapplication-tap-highlight" content="no">
    <link rel="icon" href="{{ asset('favicon.ico') }}">

    <script type="module" crossorigin src="{{ asset('template/assets/scripts/main-Df65xusa.js') }}"></script>
    <link rel="stylesheet" crossorigin href="{{ asset('template/assets/styles/main.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/styles/quantro-theme.css') }}">
    @stack('styles')
</head>

<body>
@php
    $currentUser = auth()->user();
    $hour = (int) now()->format('H');
    $sapaanWaktu = match (true) {
        $hour >= 4 && $hour < 11  => 'Selamat Pagi',
        $hour >= 11 && $hour < 15 => 'Selamat Siang',
        $hour >= 15 && $hour < 18 => 'Selamat Sore',
        default                   => 'Selamat Malam',
    };

    $sidebarHargaAktif    = \App\Models\HargaBeli::getHargaAktif();
    $countPetaniAktif     = \App\Models\Petani::where('status_aktif', true)->count();
    $countPasokanBelum    = \App\Models\PasokanTbs::where('status_pembayaran', '!=', 'lunas')->count();
    $countPengirimanAktif = \App\Models\PengirimanTbs::where('status_pengiriman', 'dikirim')->count();
    $countPiutangPks      = \App\Models\HasilTimbangPks::where('status_penerimaan', '!=', 'lunas')->count();
@endphp

    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        <!-- Mobile Top Bar (< 992px) -->
        <div class="m-mobile-topbar no-print">
            <a href="{{ url('/') }}" class="m-brand-link">
                <span class="m-brand-icon"><i class="fa fa-leaf"></i></span>
                <span class="m-brand-name">SIPADIS</span>
            </a>
            <button type="button" class="btn btn-sm btn-outline-secondary mobile-toggle-nav">
                <i class="fa fa-bars"></i>
            </button>
        </div>

        <div class="app-main">
            <!-- Left Sidebar (Merivo Structure) -->
            <div class="app-sidebar sidebar-shadow no-print">
                <div class="scrollbar-sidebar">
                    <!-- Top & Middle Navigation Wrapper -->
                    <div>
                        <!-- 1. Top Brand Logo + Collapse Button -->
                        <div class="m-brand-row">
                            <a href="{{ url('/') }}" class="m-brand-link">
                                <span class="m-brand-icon">
                                    <i class="fa fa-leaf"></i>
                                </span>
                                <span class="m-brand-name">SIPADIS</span>
                            </a>
                            <button type="button" class="m-collapse-btn close-sidebar-btn" data-class="closed-sidebar" title="Toggle Sidebar">
                                <i class="fa fa-outdent"></i>
                            </button>
                        </div>

                        <!-- 2. Store / Operational Unit Switcher Box ("Acme Store" equivalent) -->
                        <a href="{{ $currentUser && $currentUser->isPemilik() ? route('harga-beli.index') : route('pasokan-tbs.index') }}" class="m-store-switcher">
                            <div class="m-store-left">
                                <i class="fa fa-store m-store-icon"></i>
                                <div>
                                    <div class="m-store-title">RAM Sawit SIPADIS</div>
                                    <span class="m-store-sub">
                                        @if($sidebarHargaAktif)
                                            TBS: Rp {{ number_format($sidebarHargaAktif->harga_tbs, 0, ',', '.') }}/kg
                                        @else
                                            Unit Pengepul Utama
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <i class="fa fa-sort text-muted small"></i>
                        </a>

                        <!-- 3. Primary Navigation Menu -->
                        <ul class="m-nav-list">
                            <li class="m-nav-item">
                                <a href="{{ url('/') }}" class="m-nav-link {{ request()->is('/') ? 'active' : '' }}">
                                    <span class="m-nav-link-left">
                                        <i class="fa fa-th-large m-nav-icon"></i>
                                        <span>Dashboard</span>
                                    </span>
                                </a>
                            </li>

                            <li class="m-nav-item">
                                <a href="{{ route('pasokan-tbs.index') }}" class="m-nav-link {{ request()->routeIs('pasokan-tbs.*') ? 'active' : '' }}">
                                    <span class="m-nav-link-left">
                                        <i class="fa fa-weight-hanging m-nav-icon"></i>
                                        <span>Pasokan TBS</span>
                                    </span>
                                    @if($countPasokanBelum > 0)
                                        <span class="m-nav-badge">{{ $countPasokanBelum }}</span>
                                    @endif
                                </a>
                            </li>

                            <li class="m-nav-item">
                                <a href="{{ route('petani.index') }}" class="m-nav-link {{ request()->routeIs('petani.*') ? 'active' : '' }}">
                                    <span class="m-nav-link-left">
                                        <i class="fa fa-users m-nav-icon"></i>
                                        <span>Petani Mitra</span>
                                    </span>
                                    <span class="m-nav-badge">{{ $countPetaniAktif }}</span>
                                </a>
                            </li>

                            <li class="m-nav-item">
                                <a href="{{ route('pengiriman-tbs.index') }}" class="m-nav-link {{ request()->routeIs('pengiriman-tbs.*') ? 'active' : '' }}">
                                    <span class="m-nav-link-left">
                                        <i class="fa fa-truck m-nav-icon"></i>
                                        <span>Pengiriman TBS ke PKS</span>
                                    </span>
                                    @if($countPengirimanAktif > 0)
                                        <span class="m-nav-badge">{{ $countPengirimanAktif }}</span>
                                    @endif
                                </a>
                            </li>

                            <li class="m-nav-item">
                                <a href="{{ route('hasil-timbang-pks.index') }}" class="m-nav-link {{ request()->routeIs('hasil-timbang-pks.*') ? 'active' : '' }}">
                                    <span class="m-nav-link-left">
                                        <i class="fa fa-file-invoice-dollar m-nav-icon"></i>
                                        <span>Hasil Timbang PKS</span>
                                    </span>
                                </a>
                            </li>

                            <li class="m-nav-item">
                                <a href="{{ route('pks-pembeli.index') }}" class="m-nav-link {{ request()->routeIs('pks-pembeli.*') ? 'active' : '' }}">
                                    <span class="m-nav-link-left">
                                        <i class="fa fa-industry m-nav-icon"></i>
                                        <span>PKS / Pembeli</span>
                                    </span>
                                </a>
                            </li>
                        </ul>

                        <!-- 4. Grouped Section: Keuangan & Harga (Only for Pemilik) -->
                        @if(!$currentUser || $currentUser->isPemilik())
                            <div class="m-section-divider"></div>
                            <div class="m-section-header">
                                <span class="m-section-header-left">
                                    <i class="fa fa-chevron-down" style="font-size: 0.65rem;"></i>
                                    <span>Keuangan & Harga</span>
                                </span>
                                <a href="{{ route('harga-beli.create') }}" class="m-section-add" title="Tetapkan Harga Baru">
                                    <i class="fa fa-plus"></i>
                                </a>
                            </div>
                            <ul class="m-subnav-list">
                                <li class="m-nav-item">
                                    <a href="{{ route('harga-beli.index') }}" class="m-nav-link {{ request()->routeIs('harga-beli.*') ? 'active' : '' }}">
                                        <span class="m-nav-link-left">
                                            <i class="fa fa-tags m-nav-icon"></i>
                                            <span>Harga Beli Harian</span>
                                        </span>
                                        <i class="fa fa-external-link-alt text-muted" style="font-size: 0.68rem;"></i>
                                    </a>
                                </li>
                                <li class="m-nav-item">
                                    <a href="{{ route('pembayaran-petani.index') }}" class="m-nav-link {{ request()->routeIs('pembayaran-petani.*') ? 'active' : '' }}">
                                        <span class="m-nav-link-left">
                                            <i class="fa fa-money-bill-wave m-nav-icon"></i>
                                            <span>Pembayaran Petani</span>
                                        </span>
                                        @if($countPasokanBelum > 0)
                                            <span class="m-nav-badge">{{ $countPasokanBelum }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li class="m-nav-item">
                                    <a href="{{ route('penerimaan-pks.index') }}" class="m-nav-link {{ request()->routeIs('penerimaan-pks.*') ? 'active' : '' }}">
                                        <span class="m-nav-link-left">
                                            <i class="fa fa-wallet m-nav-icon"></i>
                                            <span>Penerimaan Dana PKS</span>
                                        </span>
                                        @if($countPiutangPks > 0)
                                            <span class="m-nav-badge">{{ $countPiutangPks }}</span>
                                        @endif
                                    </a>
                                </li>
                            </ul>
                        @endif
                    </div>

                    <!-- 6. Bottom User Profile + Settings + Log Out Box (Merivo Bottom-Left Card) -->
                    <div class="m-sidebar-footer">
                        <div class="m-user-card">
                            <div class="m-user-info">
                                <img src="{{ asset('template/assets/images/avatars/1.jpg') }}" alt="User" class="m-user-avatar">
                                <div class="overflow-hidden">
                                    <div class="m-user-name">{{ $currentUser->name ?? 'Admin Pengepul' }}</div>
                                    <div class="m-user-role">
                                        @if($currentUser)
                                            {{ $currentUser->isPemilik() ? 'Pemilik Usaha (Admin)' : 'Petugas Operasional' }}
                                        @else
                                            Pengelola SIPADIS
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <i class="fa fa-sort text-muted small"></i>
                        </div>

                        @if(!$currentUser || $currentUser->isPemilik())
                            <a href="{{ route('harga-beli.index') }}" class="m-footer-action">
                                <i class="fa fa-cog text-muted"></i>
                                <span>Pengaturan Harga</span>
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="m-footer-action">
                                <i class="fa fa-id-badge text-muted"></i>
                                <span>Sesi Petugas Aktif</span>
                            </a>
                        @endif

                        @auth
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="m-footer-action m-logout-btn">
                                    <i class="fa fa-sign-out-alt"></i>
                                    <span>Log Out / Keluar</span>
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="m-footer-action">
                                <i class="fa fa-sign-in-alt"></i>
                                <span>Masuk</span>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="app-main__outer">
                <div class="app-main__inner">
                    <!-- Dynamic Welcome Greeting Header ("Welcome Back, Liam" style) -->
                    <div class="m-welcome-bar no-print">
                        <div>
                            <h1 class="m-welcome-title">
                                {{ $sapaanWaktu }}, Selamat Datang Kembali, {{ $currentUser->name ?? 'Pengelola' }} 👋
                            </h1>
                            <div class="m-welcome-sub">
                                @if($currentUser && $currentUser->isPemilik())
                                    <span class="m-role-pill m-role-pemilik">
                                        <i class="fa fa-crown"></i> Sesi Pemilik Usaha
                                    </span>
                                    <span>Pantau performa pasokan petani, harga TBS, dan distribusi PKS hari ini.</span>
                                @else
                                    <span class="m-role-pill m-role-petugas">
                                        <i class="fa fa-user-check"></i> Sesi Petugas Operasional
                                    </span>
                                    <span>Selamat bertugas! Siap mencatat timbangan pasokan TBS dan pengiriman ke PKS.</span>
                                @endif
                                <span class="text-muted">&bull; {{ now()->format('d M Y, H:i') }} WIB</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <form action="{{ route('pasokan-tbs.index') }}" method="GET" class="d-none d-md-flex">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-white border-end-0"><i class="fa fa-search text-muted"></i></span>
                                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari transaksi...">
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Flash Welcome Banner Right After Login -->
                    @if(session('login_welcome'))
                        <div class="m-session-welcome-alert alert-dismissible fade show no-print" role="alert">
                            <div class="d-flex align-items-center gap-3">
                                <span class="m-brand-icon" style="width: 38px; height: 38px; flex-shrink: 0;">
                                    <i class="fa fa-hand-sparkles"></i>
                                </span>
                                <div>
                                    <div class="fw-bold text-dark">{{ session('login_welcome') }}</div>
                                    <div class="small text-muted">Sesi Anda telah aktif dan seluruh modul sesuai hak akses Anda siap digunakan.</div>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    @stack('scripts')
</body>

</html>
