@extends('admin.layouts.master')
@section('title', 'Data Bundle')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h3>Data Bundle</h3>
            <p class="text-muted mb-0">
                Kelola bundle berdasarkan aturan rekomendasi rule-based.
            </p>
        </div>

        <a href="{{ route('bundles.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Tambah Bundle
        </a>
    </div>
</div>

<div class="page-content">
<section class="section">
<div class="card">
<div class="card-header">
    <h4 class="card-title">Daftar Bundle Rule-Based</h4>
</div>

<div class="card-body">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped table-hover mb-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Bundle</th>
                    <th>Nama Bundle</th>
                    <th>Jenis Acara</th>
                    <th>Kategori Item</th>
                    <th>Kategori Adat</th>
                    <th>Gender</th>
                    <th>Rias</th>
                    <th>Budget</th>
                    <th>Harga</th>
                    {{-- <th>Output Data</th> --}}
                    <th>Status</th>
                    <th style="width: 180px;">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($bundles as $index => $bundle)
                    <tr>
                        <td>{{ $index + 1 }}</td>

                        <td class="fw-semibold">
                            {{ $bundle->bundle_code ?? '-' }}
                        </td>

                        <td class="fw-semibold">
                            {{ $bundle->bundle_name ?? '-' }}
                        </td>

                        <td>
                            {{ $bundle->jenis_acara ?? '-' }}
                        </td>

                        <td>
                            {{ $bundle->kategori_item ?? '-' }}
                        </td>

                        <td>
                            {{ $bundle->kategori_adat ?? '-' }}
                        </td>

                        <td>
                            {{ $bundle->gender ?? '-' }}
                        </td>

                        <td>
                            @if($bundle->butuh_rias)
                                <span class="badge bg-success">Ya</span>
                            @else
                                <span class="badge bg-secondary">Tidak</span>
                            @endif
                        </td>

                        <td>
                            {{ $bundle->budget_category ?? '-' }}
                        </td>

                        <td>
                            Rp{{ number_format($bundle->price ?? 0, 0, ',', '.') }}
                        </td>

                        {{-- <td>
                            @if(($bundle->is_custom ?? 0) == 1)
                                <span class="badge bg-warning text-dark">Paket Custom</span>
                            @else
                                <small class="text-muted">
                                    IF jenis_acara = "{{ $bundle->jenis_acara ?? '-' }}"
                                    AND kategori_item = "{{ $bundle->kategori_item ?? '-' }}"
                                    AND kategori_adat = "{{ $bundle->kategori_adat ?? '-' }}"
                                    AND gender = "{{ $bundle->gender ?? '-' }}"
                                    AND butuh_rias = "{{ $bundle->butuh_rias ? 'Ya' : 'Tidak' }}"
                                    AND budget = "{{ $bundle->budget_category ?? '-' }}"
                                    THEN tampilkan "{{ $bundle->bundle_name ?? '-' }}"
                                </small>
                            @endif
                        </td> --}}

                        <td>
                            @if($bundle->is_active)
                                <span class="badge bg-primary">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>

                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('bundles.edit', $bundle->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form action="{{ route('bundles.destroy', $bundle->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus bundle ini?')">
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
                        <td colspan="13" class="text-center text-muted py-4">
                            Belum ada data bundle.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</div>
</section>
</div>
@endsection
