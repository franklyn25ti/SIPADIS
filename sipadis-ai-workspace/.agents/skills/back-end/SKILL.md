---
name: back-end
description: Panduan pengembangan backend Laravel untuk proyek SIPADIS (Sistem Informasi Pengepul Kelapa Sawit).
---

# Backend Development Skill (SIPADIS Laravel)

Gunakan panduan ini saat membuat atau memodifikasi Migration, Model, Controller, Request, atau Route di proyek SIPADIS:

1. **Skema Tabel Utama:**
   - `users` (`id`, `nama`, `username`, `password`, `role`: `pemilik`|`petugas`)
   - `petani` (`id`, `nama`, `alamat`, `no_kontak`, `status_aktif`)
   - `pks_pembeli` (`id`, `nama`, `alamat`, `no_kontak`, `keterangan`)
   - `harga_beli` (`id`, `tanggal_berlaku`, `harga_tbs`, `satuan`, `keterangan`)
   - `pasokan_tbs` (`id`, `no_transaksi`, `tanggal`, `petani_id`, `berat_timbang`, `potongan`, `berat_netto`, `harga_beli`, `nilai_pembelian`, `status_pembayaran`, `user_id`)
   - `pengiriman_tbs` (`id`, `no_surat_jalan`, `tanggal_kirim`, `kendaraan`, `sopir`, `pks_id`, `status_pengiriman`)
   - `detail_pengiriman` (`id`, `pengiriman_id`, `pasokan_id`, `berat_dimuat`)
   - `hasil_timbang_pks` (`id`, `no_nota_timbang`, `pengiriman_id`, `bruto`, `tara`, `netto`, `rafaksi`, `berat_dibayar`, `harga_jual`, `nilai_penjualan`)
   - `pembayaran_petani` (`id`, `pasokan_id`, `tanggal_bayar`, `jumlah_bayar`, `metode_pembayaran`, `keterangan`)
   - `biaya_operasional` (`id`, `tanggal`, `kategori`, `keterangan`, `jumlah`, `bukti_transaksi`, `user_id`)

2. **Aturan Bisnis Wajib:**
   - `berat_netto = berat_timbang - potongan`
   - `nilai_pembelian = berat_netto * harga_beli`
   - `berat_dibayar = netto - rafaksi`
   - `nilai_penjualan = berat_dibayar * harga_jual`
   - Selalu gunakan `DB::transaction` pada proses simpan `pengiriman_tbs` + `detail_pengiriman` dan `pembayaran_petani` + update status `pasokan_tbs`.
