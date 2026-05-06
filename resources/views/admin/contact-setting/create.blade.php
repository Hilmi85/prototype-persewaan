@extends('admin.layouts.master')
@section('title', 'Tambah Contact Setting')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3>Tambah Contact Setting</h3>
            <p class="text-muted mb-0">Tambahkan kontak admin untuk customer.</p>
        </div>
        <a href="{{ route('contact-settings.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>

<div class="page-content">
<section class="section">
<div class="card">
<div class="card-header"><h4 class="card-title">Form Tambah Kontak</h4></div>
<div class="card-body">
<form action="{{ route('contact-settings.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Admin</label>
            <select name="admin_user_id" class="form-select" required>
                <option value="">Pilih Admin</option>
                @foreach($admins as $admin)
                    <option value="{{ $admin->id }}">{{ $admin->fullname }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Nama Kontak</label>
            <input type="text" name="contact_name" class="form-control" value="{{ old('contact_name') }}" required>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Nomor WhatsApp</label>
            <input type="text" name="whatsapp_number" class="form-control" value="{{ old('whatsapp_number') }}" required>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">WhatsApp URL</label>
            <input type="text" name="whatsapp_url" class="form-control" value="{{ old('whatsapp_url') }}">
        </div>
        <div class="col-md-12 mb-3">
            <label class="form-label">Template Pesan</label>
            <textarea name="message_template" rows="4" class="form-control">{{ old('message_template') }}</textarea>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Status</label>
            <select name="is_active" class="form-select" required>
                <option value="1">Aktif</option>
                <option value="0">Nonaktif</option>
            </select>
        </div>
    </div>
    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('contact-settings.index') }}" class="btn btn-light-secondary">Batal</a>
    </div>
</form>
</div>
</div>
</section>
</div>
@endsection
