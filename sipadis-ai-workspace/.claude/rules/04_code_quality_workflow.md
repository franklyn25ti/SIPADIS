# 04 - Code Quality & Git Workflow

## Alur Kerja Pengembangan (Git)
- Jangan melakukan commit langsung ke branch `main` untuk fitur besar yang belum diuji.
- Gunakan pesan commit yang deskriptif dan terstruktur, contoh:
  - `feat(pasokan): tambah form input dan kalkulasi netto otomatis`
  - `fix(pengiriman): perbaiki relasi multi-petani pada detail surat jalan`
  - `docs(plan): update progres checklist plan 01`

## Standar Kebersihan Kode
- Hindari menaruh query SQL kompleks langsung di dalam file Blade (`.blade.php`). Olah data di Controller/Service terlebih dahulu.
- Gunakan *eager loading* (`with(['petani', 'user'])`) untuk mencegah masalah *N+1 Query* pada halaman tabel riwayat dan laporan.
- Pastikan setiap form memiliki directive `@csrf` dan menggunakan `@method('PUT')` / `@method('DELETE')` sesuai standar RESTful Laravel.
