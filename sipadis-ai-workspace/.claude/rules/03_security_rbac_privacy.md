# 03 - Security, RBAC & Data Privacy

## Role-Based Access Control (RBAC)
Sistem membagi hak akses ke dalam dua peran utama pada tabel `users` (`role`):
1. **`pemilik` (Pemilik Usaha / Admin):**
   - Akses penuh ke seluruh rute dan modul.
   - Eksklusif untuk: Manajemen Akun Pengguna, Pengaturan Harga Beli Harian (`harga_beli`), Verifikasi Selisih Timbangan, dan Laporan Keuangan / Laba-Rugi.
2. **`petugas` (Petugas Pencatatan / Operasional):**
   - Diizinkan untuk: Melihat daftar Petani & PKS, mencatat Pasokan TBS masuk, membuat Surat Jalan Pengiriman, dan menginput Nota Timbang PKS.
   - **Dilarang keras** mengakses URL pengaturan akun, ubah harga beli harian, dan laporan laba-rugi.

## Validasi & Integritas Data
- Semua rute aplikasi wajib dilindungi middleware `auth`.
- Gunakan middleware kustom `role:pemilik` untuk memproteksi rute sensitif di `routes/web.php`.
- Password wajib di-hash menggunakan `Hash::make()` (Bcrypt/Argon2).
- Validasi input server-side wajib menolak angka negatif (`min:0`) pada berat, potongan, harga, dan nominal pembayaran.
