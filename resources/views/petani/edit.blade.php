@extends('app')

@section('content')
<div class="main-card mb-3 card">
    <div class="card-header">Edit Data Petani</div>
    <div class="card-body">
        <form action="{{ route('petani.update', $petani->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Nama Petani</label>
                <input type="text" name="nama_petani" class="form-control" value="{{ $petani->nama_petani }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">No. HP / WhatsApp</label>
                <input type="text" name="no_hp" class="form-control" value="{{ $petani->no_hp }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Luas Lahan (Hektar)</label>
                <input type="number" step="0.01" name="luas_lahan" class="form-control" value="{{ $petani->luas_lahan }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Alamat / Lokasi Kebun</label>
                <textarea name="alamat" class="form-control" rows="3">{{ $petani->alamat }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Perbarui Data</button>
            <a href="{{ route('petani.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection