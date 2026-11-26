# Task 2.4: Pengujian Sistem (Testing) & Finalisasi

## Checklist Pengerjaan
- [ ] **Uji Hak Akses:** Login sebagai `petugas` dan pastikan akses ditolak (403 Forbidden) saat membuka URL `/harga-beli` dan `/laporan/laba-rugi`.
- [ ] **Uji Validasi Input:** Coba submit berat bernilai `0` atau negatif, pastikan sistem menampilkan pesan error validasi.
- [ ] **Uji Skenario Multi-Petani:** Buat 1 surat jalan berisi pasokan dari 3 petani berbeda, input hasil timbang PKS dengan rafaksi, dan pastikan selisih tonase terhitung akurat.
- [ ] **Uji Pembayaran Bertahap:** Bayar 50% tagihan petani (cek status `dibayar_sebagian`), lalu lunasi 50% sisanya (cek status `lunas`).
- [ ] **Uji Rekonsiliasi Laporan:** Cocokkan angka laporan laba-rugi di aplikasi dengan perhitungan manual kalkulator/Excel.
