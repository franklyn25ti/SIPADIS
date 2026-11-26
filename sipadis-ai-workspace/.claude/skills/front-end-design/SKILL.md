---
name: front-end-design
description: Panduan desain antarmuka Blade + Bootstrap 5 untuk sistem operasional pengepul kelapa sawit.
---

# Frontend Design Skill (SIPADIS UI)

Gunakan standar ini saat membangun tampilan Blade (`resources/views/`):

1. **Struktur Halaman:**
   - Selalu `@extends('layouts.app')` dan isi `@section('content')`.
   - Gunakan komponen Card Bootstrap (`card shadow-sm border-0`) untuk membungkus form dan tabel data.
2. **UX Form Lapangan:**
   - Berikan label satuan yang jelas di sebelah input (`kg`, `Rp/kg`, `%`).
   - Buat input hasil kalkulasi otomatis (`berat_netto`, `nilai_pembelian`, `berat_dibayar`) bersifat `readonly` dengan latar belakang abu-abu muda (`bg-light`) dan diperbarui secara real-time lewat event listener JavaScript `input`.
3. **Status Badge:**
   - `belum_dibayar` -> `<span class="badge bg-danger">Belum Dibayar</span>`
   - `dibayar_sebagian` -> `<span class="badge bg-warning text-dark">Dibayar Sebagian</span>`
   - `lunas` -> `<span class="badge bg-success">Lunas</span>`
