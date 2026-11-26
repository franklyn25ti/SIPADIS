# Task 1.3: Modul Pencatatan Pasokan & Pembelian TBS

## Checklist Pengerjaan
- [x] Buat `PasokanTbsController` dan view di `resources/views/transaksi/pasokan/`.
- [x] Generate nomor transaksi otomatis (contoh: `PSK-20261124-001`).
- [x] Pada form input pasokan:
  - [x] Dropdown pilih petani aktif.
  - [x] Input `berat_timbang` (kg) dan `potongan` (kg).
  - [x] Otomatis tampilkan `harga_beli` hari ini (dapat disesuaikan jika ada kebijakan khusus).
  - [x] Kalkulasi otomatis `berat_netto` dan `nilai_pembelian` via JavaScript & validasi ulang di Controller.
- [x] Simpan transaksi dengan status default `belum_dibayar` dan catat `user_id` petugas yang menginput.
- [x] Tampilkan tabel riwayat pasokan dengan filter tanggal dan petani.
