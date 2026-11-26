# 02 - Frontend Design System (Blade & Bootstrap 5)

## Prinsip Antarmuka (UI/UX)
- **Responsif:** Antarmuka harus optimal dibuka di layar desktop (admin/pemilik) maupun perangkat mobile/tablet (petugas timbangan di lapangan).
- **Layout Utama:** Gunakan layout terpusat di `resources/views/layouts/app.blade.php` dengan komponen partial (`navbar.blade.php`, `sidebar.blade.php`, `footer.blade.php`).
- **Istilah Lapangan:** Gunakan istilah operasional yang familier bagi pengepul sawit Indonesia: *Bruto, Tara, Netto, Rafaksi, Surat Jalan, PKS, TBS*.

## Komponen & Interaktivitas Form
- **Kalkulasi Real-Time (JavaScript):**
  - Pada form input pasokan TBS, hitung otomatis `berat_netto = berat_timbang - potongan` dan `nilai_pembelian = berat_netto * harga_beli` saat pengguna mengetik.
  - Pada form hasil timbang PKS, hitung otomatis `berat_dibayar = netto - rafaksi`, `nilai_penjualan = berat_dibayar * harga_jual`, serta `selisih_berat = berat_dibayar - total_berat_kirim`.
- **Feedback Pengguna:** Tampilkan alert notifikasi sukses/gagal (Bootstrap Toast/Alert) setelah setiap aksi CRUD, serta konfirmasi modal sebelum menghapus data transaksi.
