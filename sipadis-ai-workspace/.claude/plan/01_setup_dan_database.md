# Task 1.1: Setup Database, Migrasi & Autentikasi RBAC

## Checklist Pengerjaan
- [ ] Buat migration untuk seluruh tabel inti (`users`, `petani`, `pks_pembeli`, `harga_beli`, `pasokan_tbs`, `pengiriman_tbs`, `detail_pengiriman`, `hasil_timbang_pks`, `pembayaran_petani`, `biaya_operasional`).
- [ ] Tambahkan kolom `role` (`enum('pemilik', 'petugas')`) pada tabel `users`.
- [ ] Buat `DatabaseSeeder` berisi 1 akun `pemilik` dan 1 akun `petugas` untuk pengujian.
- [ ] Buat middleware `CheckRole` dan daftarkan alias `role` di `bootstrap/app.php`.
- [ ] Siapkan layout utama `resources/views/layouts/app.blade.php` dengan Bootstrap 5 dan navigasi sidebar dinamis sesuai role user.
