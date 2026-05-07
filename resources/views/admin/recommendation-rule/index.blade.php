@extends('admin.layouts.master')

@section('title', 'Data Recommendation Rule')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h3>Data Rule Rekomendasi</h3>
            <p class="text-muted mb-0">
                Kelola aturan IF-THEN berdasarkan jenis acara, kategori adat, gender, kebutuhan rias, dan budget.
            </p>
        </div>

        <a href="{{ route('recommendation-rules.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Tambah Rule
        </a>
    </div>
</div>

<div class="page-content">
    <section class="section">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Daftar Rule-Based Recommendation</h4>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama Rule</th>
                                <th>Output Bundle</th>
                                <th>Kondisi IF</th>
                                <th>Prioritas</th>
                                <th>Status</th>
                                <th style="width: 160px;">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($rules as $index => $rule)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            {{ $rule->rule_code ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="fw-semibold">{{ $rule->rule_name }}</td>
                                    <td>{{ $rule->bundle->bundle_name ?? '-' }}</td>
                                    <td>
                                        <div class="small">
                                            <div>Jenis Acara: <strong>{{ $rule->jenis_acara ?? 'Semua' }}</strong></div>
                                            <div>Adat: <strong>{{ $rule->kategori_adat ?? 'Semua' }}</strong></div>
                                            <div>Gender: <strong>{{ $rule->gender ?? 'Semua' }}</strong></div>
                                            <div>Rias: <strong>{{ is_null($rule->butuh_rias) ? 'Semua' : ($rule->butuh_rias ? 'Ya' : 'Tidak') }}</strong></div>
                                            <div>Budget: <strong>{{ $rule->budget ?? 'Semua' }}</strong></div>
                                        </div>
                                    </td>
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
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        Belum ada rule rekomendasi.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="alert alert-info mt-4 mb-0">
                    <strong>Catatan:</strong>
                    Kolom yang dikosongkan akan dianggap sebagai wildcard atau berlaku untuk semua kondisi.
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
