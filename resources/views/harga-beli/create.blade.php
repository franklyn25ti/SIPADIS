@extends('app')

@section('content')
<div class="main-card mb-3 card shadow-sm border-0">
    <div class="card-header">Tetapkan Harga Beli TBS Baru</div>
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

        <form action="{{ route('harga-beli.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Tanggal Berlaku <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_berlaku" class="form-control" value="{{ old('tanggal_berlaku', now()->toDateString()) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Harga Beli TBS (Rp/kg) <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="number" step="0.01" min="0" name="harga_tbs" class="form-control" value="{{ old('harga_tbs') }}" placeholder="Contoh: 2450" required>
                    <span class="input-group-text">/ kg</span>
                </div>
            </div>
            <input type="hidden" name="satuan" value="kg">
            <div class="mb-3">
                <label class="form-label">Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="3" placeholder="Catatan penetapan harga beli...">{{ old('keterangan') }}</textarea>
            </div>
            <button type="submit" class="btn btn-success">Simpan Harga</button>
            <a href="{{ route('harga-beli.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
