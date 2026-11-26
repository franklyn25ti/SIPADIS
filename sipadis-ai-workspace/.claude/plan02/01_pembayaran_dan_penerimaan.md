# Task 2.1: Modul Pembayaran Petani & Penerimaan PKS

## Checklist Pengerjaan
- [x] **Pembayaran Petani (`PembayaranPetaniController`):**
  - [x] Tampilkan daftar pasokan petani yang berstatus `belum_dibayar` atau `dibayar_sebagian` beserta sisa tagihannya.
  - [x] Form input pembayaran (`tanggal_bayar`, `jumlah_bayar`, `metode_pembayaran`, `keterangan`).
  - [x] Logika otomatis: jika akumulasi `jumlah_bayar >= nilai_pembelian`, ubah status `pasokan_tbs` menjadi `lunas`; jika kurang, ubah menjadi `dibayar_sebagian`.
- [x] **Penerimaan Dana PKS:**
  - [x] Catat pembayaran masuk dari PKS atas nota timbang yang sudah terbit dan pantau sisa piutang pabrik.
