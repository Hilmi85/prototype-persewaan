@extends('admin.layouts.master')
@section('title', 'Tambah Recommendation Rule')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3>Tambah Rule Rekomendasi</h3>
            <p class="text-muted mb-0">Tambahkan aturan baru untuk sistem rule-based.</p>
        </div>
        <a href="{{ route('recommendation-rules.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>

<div class="page-content">
<section class="section">
<div class="card">
<div class="card-header"><h4 class="card-title">Form Tambah Rule</h4></div>
<div class="card-body">
<form action="{{ route('recommendation-rules.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Kode Rule</label>
            <input type="text" name="rule_code" class="form-control" value="{{ old('rule_code') }}">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Nama Rule</label>
            <input type="text" name="rule_name" class="form-control" value="{{ old('rule_name') }}">
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Bundle</label>
            <select name="bundle_id" class="form-select">
                <option value="">Pilih Bundle</option>
                @foreach($bundles as $bundle)
                    <option value="{{ $bundle->id }}">{{ $bundle->bundle_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Jenis Acara</label>
            <input type="text" name="jenis_acara" class="form-control" value="{{ old('jenis_acara') }}">
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Kategori Adat</label>
            <input type="text" name="kategori_adat" class="form-control" value="{{ old('kategori_adat') }}">
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Gender</label>
            <select name="gender" class="form-select">
                <option value="">Pilih Gender</option>
                <option value="Laki-laki">Laki-laki</option>
                <option value="Perempuan">Perempuan</option>
            </select>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Budget</label>
            <select name="budget" class="form-select">
                <option value="">Pilih Budget</option>
                <option value="Rendah">Rendah</option>
                <option value="Sedang">Sedang</option>
                <option value="Tinggi">Tinggi</option>
            </select>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">Butuh Rias</label>
            <select name="butuh_rias" class="form-select">
                <option value="">Pilih</option>
                <option value="1">Ya</option>
                <option value="0">Tidak</option>
            </select>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Size (Opsional)</label>
            <input type="text" name="size" class="form-control" value="{{ old('size') }}">
        </div>
        <div class="col-md-2 mb-3">
            <label class="form-label">Prioritas</label>
            <input type="number" name="priority" class="form-control" min="1" value="{{ old('priority', 1) }}" required>
        </div>
        <div class="col-md-2 mb-3">
            <label class="form-label">Status</label>
            <select name="is_active" class="form-select" required>
                <option value="1">Aktif</option>
                <option value="0">Nonaktif</option>
            </select>
        </div>

        <div class="col-12 mb-3">
            <label class="form-label">Catatan</label>
            <textarea name="notes" rows="4" class="form-control">{{ old('notes') }}</textarea>
        </div>
    </div>
    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('recommendation-rules.index') }}" class="btn btn-light-secondary">Batal</a>
    </div>
</form>
</div>
</div>
</section>
</div>
@endsection
