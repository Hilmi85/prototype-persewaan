@extends('admin.layouts.master')
@section('title', 'Edit Contact Setting')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3>Edit Contact Setting</h3>
            <p class="text-muted mb-0">Perbarui kontak admin.</p>
        </div>
        <a href="{{ route('contact-settings.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>

<div class="page-content">
<section class="section">
<div class="card">
<div class="card-header"><h4 class="card-title">Form Edit Kontak</h4></div>
<div class="card-body">
<form action="{{ route('contact-settings.update', $contactSetting->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Admin</label>
            <select name="admin_user_id" class="form-select" required>
                @foreach($admins as $admin)
                    <option value="{{ $admin->id }}" {{ old('admin_user_id', $contactSetting->admin_user_id) == $admin->id ? 'selected' : '' }}>
                        {{ $admin->fullname }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Nama Kontak</label>
            <input type="text" name="contact_name" class="form-control" value="{{ old('contact_name', $contactSetting->contact_name) }}" required>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Nomor WhatsApp</label>
            <input type="text" name="whatsapp_number" class="form-control" value="{{ old('whatsapp_number', $contactSetting->whatsapp_number) }}" required>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">WhatsApp URL</label>
            <input type="text" name="whatsapp_url" class="form-control" value="{{ old('whatsapp_url', $contactSetting->whatsapp_url) }}">
        </div>
        <div class="col-md-12 mb-3">
            <label class="form-label">Template Pesan</label>
            <textarea name="message_template" rows="4" class="form-control">{{ old('message_template', $contactSetting->message_template) }}</textarea>
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Status</label>
            <select name="is_active" class="form-select" required>
                <option value="1" {{ old('is_active', $contactSetting->is_active) == 1 ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ old('is_active', $contactSetting->is_active) == 0 ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>
    </div>
    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('contact-settings.index') }}" class="btn btn-light-secondary">Batal</a>
    </div>
</form>
</div>
</div>
</section>
</div>
@endsection
