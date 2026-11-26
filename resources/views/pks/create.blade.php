@extends('app')

@section('content')
<div class="main-card mb-3 card shadow-sm border-0">
    <div class="card-header">Tambah Data PKS / Pembeli Baru</div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('pks-pembeli.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nama PKS / Pabrik <span class="text-danger">*</span></label>
                <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" placeholder="Contoh: PT. Sawit Makmur Jaya (PKS 1)" required>
            </div>
            <div class="mb-3">
                <label class="form-label">No. Kontak / Telepon</label>
                <input type="text" name="no_kontak" class="form-control" value="{{ old('no_kontak') }}" placeholder="Contoh: 0812-xxxx-xxxx">
            </div>
            <div class="mb-3">
                <label class="form-label">Alamat Pabrik</label>
                <textarea name="alamat" class="form-control" rows="3" placeholder="Alamat lengkap lokasi pabrik...">{{ old('alamat') }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Keterangan Tambahan</label>
                <textarea name="keterangan" class="form-control" rows="2" placeholder="Catatan jam operasional timbangan, syarat rafaksi, dll.">{{ old('keterangan') }}</textarea>
            </div>
            <button type="submit" class="btn btn-success">Simpan Data</button>
            <a href="{{ route('pks-pembeli.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
