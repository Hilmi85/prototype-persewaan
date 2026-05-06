@extends('admin.layouts.master')
@section('title', 'Data Recommendation Rule')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3>Data Rule Rekomendasi</h3>
            <p class="text-muted mb-0">Kelola aturan rule-based untuk sistem rekomendasi paket.</p>
        </div>
        <a href="{{ route('recommendation-rules.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Tambah Rule
        </a>
    </div>
</div>

<div class="page-content">
<section class="section">
<div class="card">
<div class="card-header"><h4 class="card-title">Daftar Rule</h4></div>
<div class="card-body">
    <div class="table-responsive">
        <table class="table table-striped table-hover mb-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama Rule</th>
                    <th>Bundle</th>
                    <th>Jenis Acara</th>
                    <th>Adat</th>
                    <th>Gender</th>
                    <th>Rias</th>
                    <th>Budget</th>
                    <th>Prioritas</th>
                    <th>Status</th>
                    <th style="width: 180px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rules as $index => $rule)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $rule->rule_code ?? '-' }}</td>
                        <td>{{ $rule->rule_name ?? '-' }}</td>
                        <td>{{ $rule->bundle->bundle_name ?? '-' }}</td>
                        <td>{{ $rule->jenis_acara ?? '-' }}</td>
                        <td>{{ $rule->kategori_adat ?? '-' }}</td>
                        <td>{{ $rule->gender ?? '-' }}</td>
                        <td>{{ is_null($rule->butuh_rias) ? '-' : ($rule->butuh_rias ? 'Ya' : 'Tidak') }}</td>
                        <td>{{ $rule->budget ?? '-' }}</td>
                        <td>{{ $rule->priority }}</td>
                        <td>
                            @if($rule->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('recommendation-rules.edit', $rule->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('recommendation-rules.destroy', $rule->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus rule ini?')">
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
                    <tr><td colspan="12" class="text-center text-muted py-4">Belum ada rule rekomendasi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</div>
</section>
</div>
@endsection
