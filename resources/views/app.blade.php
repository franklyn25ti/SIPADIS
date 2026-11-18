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
</head>

<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        <div class="app-header header-shadow">
            <div class="app-header__logo">
                <a href="{{ url('/') }}" class="text-decoration-none d-flex align-items-center">
                    <span class="fw-bolder fs-4 text-success" style="letter-spacing: 1px;">
                        <i class="pe-7s-leaf me-1"></i>SIPADIS
                    </span>
                </a>
                <div class="header__pane ms-auto">
                    <div>
                        <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="app-header__mobile-menu">
                <div>
                    <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                        <span class="hamburger-box">
                            <span class="hamburger-inner"></span>
                        </span>
                    </button>
                </div>
            </div>
            <div class="app-header__menu">
                <span>
                    <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                        <span class="btn-icon-wrapper">
                            <i class="fa fa-ellipsis-v fa-w-6"></i>
                        </span>
                    </button>
                </span>
            </div>
            <div class="app-header__content">
                <div class="app-header-left">
                    <div class="search-wrapper">
                        <div class="input-holder">
                            <input type="text" class="search-input" placeholder="Cari data...">
                            <button class="search-icon"><span></span></button>
                        </div>
                        <button class="btn-close"></button>
                    </div>
                </div>
                <div class="app-header-right">
                    <div class="header-btn-lg pe-0">
                        <div class="widget-content p-0">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="btn-group">
                                        <a data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                                            <img width="42" class="rounded-circle" src="{{ asset('template/assets/images/avatars/1.jpg') }}" alt="">
                                            <i class="fa fa-angle-down ms-2 opacity-8"></i>
                                        </a>
                                        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
                                            <button type="button" tabindex="0" class="dropdown-item">Profil Akun</button>
                                            <button type="button" tabindex="0" class="dropdown-item">Pengaturan</button>
                                            <div tabindex="-1" class="dropdown-divider"></div>
                                            <button type="button" tabindex="0" class="dropdown-item text-danger">Keluar</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="widget-content-left ms-3 header-user-info">
                                    <div class="widget-heading">
                                        Admin Pengepul
                                    </div>
                                    <div class="widget-subheading">
                                        Pengelola SIPADIS
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-main">
            <div class="app-sidebar sidebar-shadow">
                <div class="app-header__logo">
                    <a href="{{ url('/') }}" class="text-decoration-none d-flex align-items-center">
                        <span class="fw-bolder fs-4 text-success" style="letter-spacing: 1px;">
                            <i class="pe-7s-leaf me-1"></i>SIPADIS
                        </span>
                    </a>
                    <div class="header__pane ms-auto">
                        <div>
                            <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                                <span class="hamburger-box">
                                    <span class="hamburger-inner"></span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="app-header__mobile-menu">
                    <div>
                        <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
                <div class="app-header__menu">
                    <span>
                        <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                            <span class="btn-icon-wrapper">
                                <i class="fa fa-ellipsis-v fa-w-6"></i>
                            </span>
                        </button>
                    </span>
                </div>
                <div class="scrollbar-sidebar">
                    <div class="app-sidebar__inner">
                        <ul class="vertical-nav-menu">
                            <li class="app-sidebar__heading">Menu Utama</li>
                            <li>
                                <a href="{{ url('/') }}" class="mm-active">
                                    <i class="metismenu-icon pe-7s-rocket"></i>
                                    Dashboard
                                </a>
                            </li>
                            <li class="app-sidebar__heading">Operasional Sawit</li>
                            <li>
                                <a href="{{ route('petani.index') }}" class="{{ request()->routeIs('petani.*') ? 'mm-active' : '' }}">
                                    <i class="metismenu-icon pe-7s-users"></i>
                                    Data Petani
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="metismenu-icon pe-7s-box2"></i>
                                    Pasokan TBS
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="metismenu-icon pe-7s-car"></i>
                                    Distribusi Pabrik (PKS)
                                </a>
                            </li>
                            <li class="app-sidebar__heading">Keuangan & Laporan</li>
                            <li>
                                <a href="#">
                                    <i class="metismenu-icon pe-7s-cash"></i>
                                    Harga & Pembayaran
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <i class="metismenu-icon pe-7s-graph2"></i>
                                    Laporan Keuangan
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="app-main__outer">
                <div class="app-main__inner">
                    @yield('content')
                </div>
                <div class="app-wrapper-footer">
                    <div class="app-footer">
                        <div class="app-footer__inner">
                            <div class="app-footer-left">
                                <span class="text-muted">SIPADIS &copy; {{ date('Y') }} - Sistem Informasi Pengepul Kelapa Sawit</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>