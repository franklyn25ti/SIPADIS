<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="id">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Login - SIPADIS (Sistem Informasi Pengepul Kelapa Sawit)</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <script type="module" crossorigin src="{{ asset('template/assets/scripts/main-Df65xusa.js') }}"></script>
    <link rel="stylesheet" crossorigin href="{{ asset('template/assets/styles/main.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/styles/quantro-theme.css') }}">
</head>

<body>
    <div class="min-vh-100 d-flex justify-content-center align-items-center py-4" style="background-color: #131417;">
        <div class="col-11 col-sm-8 col-md-5 col-lg-4">
            <div class="card border-0 shadow-lg" style="border-radius: 24px; background-color: #ffffff;">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center gap-2 mb-2">
                            <span class="q-brand-badge">
                                <i class="fa fa-chart-bar"></i>
                            </span>
                            <span class="fw-bolder fs-3 text-dark">SIPADIS</span>
                        </div>
                        <div class="text-muted small">
                            Sistem Informasi Pengepul Kelapa Sawit
                        </div>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger py-2 small rounded-3">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.post') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="login" class="form-label fw-semibold small text-secondary">Username atau Email</label>
                            <input type="text" id="login" name="login" class="form-control @error('login') is-invalid @enderror" value="{{ old('login') }}" placeholder="Masukkan username atau email" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold small text-secondary">Password</label>
                            <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan password" required>
                        </div>
                        <div class="form-check mb-4">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label small text-muted" for="remember">Ingat Saya</label>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-q-dark py-2 fw-bold">
                                Masuk ke Sistem <i class="fa fa-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">
                    <div class="p-3 rounded-4" style="background-color: #f7f8fa;">
                        <div class="fw-bold small text-dark mb-1">Akun Demo Pengujian:</div>
                        <div class="small text-muted d-flex justify-content-between">
                            <span>Pemilik: <code>pemilik</code></span>
                            <span>Password: <code>password</code></span>
                        </div>
                        <div class="small text-muted d-flex justify-content-between mt-1">
                            <span>Petugas: <code>petugas</code></span>
                            <span>Password: <code>password</code></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
