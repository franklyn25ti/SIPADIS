# Task 1.2: Modul Master Data

## Checklist Pengerjaan
- [x] **Petani Mitra (`PetaniController`):**
  - [x] Halaman daftar petani dengan pencarian nama/alamat dan status aktif.
  - [x] Form tambah & edit data petani (`nama`, `alamat`, `no_kontak`, `status_aktif`).
- [x] **PKS / Pembeli (`PksPembeliController`):**
  - [x] Halaman daftar pabrik tujuan pengiriman TBS.
  - [x] Form tambah & edit data PKS (`nama`, `alamat`, `no_kontak`, `keterangan`).
- [x] **Harga Beli Harian (`HargaBeliController` - Khusus Role `pemilik`):**
  - [x] Form penetapan harga beli TBS per kg berdasarkan `tanggal_berlaku`.
  - [x] Helper/method pada model `HargaBeli` untuk mengambil harga aktif terbaru secara otomatis saat petugas menginput pasokan.
