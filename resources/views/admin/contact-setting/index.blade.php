@extends('admin.layouts.master')
@section('title', 'Contact Setting')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3>Contact Setting</h3>
            <p class="text-muted mb-0">Kelola kontak admin yang ditampilkan ke pelanggan.</p>
        </div>
        <a href="{{ route('contact-settings.create') }}" class="btn btn-primary">Tambah Kontak</a>
    </div>
</div>

<div class="page-content">
<section class="section">
<div class="card">
<div class="card-header"><h4 class="card-title">Daftar Kontak</h4></div>
<div class="card-body">
    <div class="table-responsive">
        <table class="table table-striped table-hover mb-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Admin</th>
                    <th>Nama Kontak</th>
                    <th>WhatsApp</th>
                    <th>URL</th>
                    <th>Status</th>
                    <th style="width:180px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contacts as $index => $contact)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $contact->admin->fullname ?? '-' }}</td>
                        <td>{{ $contact->contact_name }}</td>
                        <td>{{ $contact->whatsapp_number }}</td>
                        <td>{{ $contact->whatsapp_url ?? '-' }}</td>
                        <td>
                            @if($contact->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('contact-settings.edit', $contact->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('contact-settings.destroy', $contact->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kontak ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data kontak.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</div>
</section>
</div>
@endsection
