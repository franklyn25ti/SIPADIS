# Task 1.2: Modul Master Data

## Checklist Pengerjaan
- [ ] **Petani Mitra (`PetaniController`):**
  - [ ] Halaman daftar petani dengan pencarian nama/alamat dan status aktif.
  - [ ] Form tambah & edit data petani (`nama`, `alamat`, `no_kontak`, `status_aktif`).
- [ ] **PKS / Pembeli (`PksPembeliController`):**
  - [ ] Halaman daftar pabrik tujuan pengiriman TBS.
  - [ ] Form tambah & edit data PKS (`nama`, `alamat`, `no_kontak`, `keterangan`).
- [ ] **Harga Beli Harian (`HargaBeliController` - Khusus Role `pemilik`):**
  - [ ] Form penetapan harga beli TBS per kg berdasarkan `tanggal_berlaku`.
  - [ ] Helper/method pada model `HargaBeli` untuk mengambil harga aktif terbaru secara otomatis saat petugas menginput pasokan.
