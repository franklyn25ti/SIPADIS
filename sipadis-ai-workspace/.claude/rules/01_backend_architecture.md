# 01 - Backend Architecture (Laravel & MySQL)

## Tech Stack & Standar Kode
- **Framework:** Laravel (PHP 8.3) pada environment Laragon (`C:\laragon\www\SIPADIS`).
- **Database:** MySQL (`db_pengepul_sawit`).
- **Arsitektur:** MVC standar Laravel dengan pemisahan logika bisnis kompleks ke dalam `app/Services/` atau Form Request validation (`app/Http/Requests/`).

## Konvensi Penamaan & Struktur
- **Controllers:** Gunakan Resource Controller standar (`PetaniController`, `PasokanTbsController`, `PengirimanTbsController`, `HasilTimbangPksController`, `PembayaranPetaniController`, `BiayaOperasionalController`, `LaporanController`).
- **Models:** Gunakan relasi Eloquent yang eksplisit (`hasMany`, `belongsTo`) serta `$fillable` atau `$guarded` yang ketat.
- **Database & Migrations:**
  - Gunakan tipe data `decimal(12, 2)` atau `decimal(15, 2)` untuk nominal uang (harga beli, harga jual, nilai pembelian, biaya operasional).
  - Gunakan tipe data `decimal(10, 2)` untuk satuan berat dalam kilogram (`berat_timbang`, `potongan`, `berat_netto`, `bruto`, `tara`, `netto`, `rafaksi`, `berat_dibayar`).
  - Wajib gunakan `DB::transaction()` untuk operasi yang melibatkan banyak tabel sekaligus (misal: membuat `pengiriman_tbs` beserta banyak baris `detail_pengiriman`, atau mencatat `pembayaran_petani` sekaligus memperbarui `status_pembayaran` pada `pasokan_tbs`).
