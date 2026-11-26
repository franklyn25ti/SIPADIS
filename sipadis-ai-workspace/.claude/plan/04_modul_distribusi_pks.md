# Task 1.4: Modul Distribusi Surat Jalan & Hasil Timbang PKS

## Checklist Pengerjaan
- [ ] **Pengiriman & Surat Jalan (`PengirimanTbsController`):**
  - [ ] Form pembuatan surat jalan (`no_surat_jalan`, `tanggal_kirim`, `kendaraan`, `sopir`, `pks_id`).
  - [ ] Fitur pilih/centang transaksi `pasokan_tbs` yang dimuat ke dalam truk (menyimpan ke `detail_pengiriman` menggunakan `DB::transaction`).
  - [ ] Halaman cetak Surat Jalan siap print (`window.print()`).
- [ ] **Hasil Timbang PKS (`HasilTimbangPksController`):**
  - [ ] Form input nota timbang pabrik berdasarkan pengiriman yang berstatus `dikirim`.
  - [ ] Input `no_nota_timbang`, `bruto`, `tara`, `netto`, `rafaksi`, dan `harga_jual`.
  - [ ] Hitung otomatis `berat_dibayar = netto - rafaksi` dan `nilai_penjualan = berat_dibayar * harga_jual`.
  - [ ] Tampilkan indikator **Selisih Berat** (`berat_dibayar` vs total `berat_dimuat` dari petani) dan ubah status pengiriman menjadi `selesai_timbang`.
