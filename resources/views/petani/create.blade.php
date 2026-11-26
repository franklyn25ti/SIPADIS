@extends('app')

@section('content')
<div class="main-card mb-3 card shadow-sm border-0">
    <div class="card-header">Tambah Data Petani Baru</div>
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

        <form action="{{ route('petani.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nama Petani <span class="text-danger">*</span></label>
                <input type="text" name="nama_petani" class="form-control" value="{{ old('nama_petani') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">No. HP / WhatsApp</label>
                <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp') }}">
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Luas Lahan (Hektar) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0" name="luas_lahan" class="form-control" value="{{ old('luas_lahan', 0) }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Status Kemitraan</label>
                    <select name="status_aktif" class="form-select">
                        <option value="1" {{ old('status_aktif', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('status_aktif') == '0' ? 'selected' : '' }}>Non-Aktif</option>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Alamat / Lokasi Kebun</label>
                <textarea name="alamat" class="form-control" rows="3">{{ old('alamat') }}</textarea>
            </div>
            <button type="submit" class="btn btn-success">Simpan Data</button>
            <a href="{{ route('petani.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
