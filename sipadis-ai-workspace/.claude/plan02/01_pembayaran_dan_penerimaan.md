# Task 2.1: Modul Pembayaran Petani & Penerimaan PKS

## Checklist Pengerjaan
- [ ] **Pembayaran Petani (`PembayaranPetaniController`):**
  - [ ] Tampilkan daftar pasokan petani yang berstatus `belum_dibayar` atau `dibayar_sebagian` beserta sisa tagihannya.
  - [ ] Form input pembayaran (`tanggal_bayar`, `jumlah_bayar`, `metode_pembayaran`, `keterangan`).
  - [ ] Logika otomatis: jika akumulasi `jumlah_bayar >= nilai_pembelian`, ubah status `pasokan_tbs` menjadi `lunas`; jika kurang, ubah menjadi `dibayar_sebagian`.
- [ ] **Penerimaan Dana PKS:**
  - [ ] Catat pembayaran masuk dari PKS atas nota timbang yang sudah terbit dan pantau sisa piutang pabrik.
