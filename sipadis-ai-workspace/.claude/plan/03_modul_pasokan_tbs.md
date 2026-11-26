# Task 1.3: Modul Pencatatan Pasokan & Pembelian TBS

## Checklist Pengerjaan
- [ ] Buat `PasokanTbsController` dan view di `resources/views/transaksi/pasokan/`.
- [ ] Generate nomor transaksi otomatis (contoh: `PSK-20261124-001`).
- [ ] Pada form input pasokan:
  - [ ] Dropdown pilih petani aktif.
  - [ ] Input `berat_timbang` (kg) dan `potongan` (kg).
  - [ ] Otomatis tampilkan `harga_beli` hari ini (dapat disesuaikan jika ada kebijakan khusus).
  - [ ] Kalkulasi otomatis `berat_netto` dan `nilai_pembelian` via JavaScript & validasi ulang di Controller.
- [ ] Simpan transaksi dengan status default `belum_dibayar` dan catat `user_id` petugas yang menginput.
- [ ] Tampilkan tabel riwayat pasokan dengan filter tanggal dan petani.
