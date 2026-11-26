# Task 1.4: Modul Distribusi Surat Jalan & Hasil Timbang PKS

## Checklist Pengerjaan
- [x] **Pengiriman & Surat Jalan (`PengirimanTbsController`):**
  - [x] Form pembuatan surat jalan (`no_surat_jalan`, `tanggal_kirim`, `kendaraan`, `sopir`, `pks_id`).
  - [x] Fitur pilih/centang transaksi `pasokan_tbs` yang dimuat ke dalam truk (menyimpan ke `detail_pengiriman` menggunakan `DB::transaction`).
  - [x] Halaman cetak Surat Jalan siap print (`window.print()`).
- [x] **Hasil Timbang PKS (`HasilTimbangPksController`):**
  - [x] Form input nota timbang pabrik berdasarkan pengiriman yang berstatus `dikirim`.
  - [x] Input `no_nota_timbang`, `bruto`, `tara`, `netto`, `rafaksi`, dan `harga_jual`.
  - [x] Hitung otomatis `berat_dibayar = netto - rafaksi` dan `nilai_penjualan = berat_dibayar * harga_jual`.
  - [x] Tampilkan indikator **Selisih Berat** (`berat_dibayar` vs total `berat_dimuat` dari petani) dan ubah status pengiriman menjadi `selesai_timbang`.
