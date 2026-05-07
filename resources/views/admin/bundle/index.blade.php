@extends('admin.layouts.master')
@section('title', 'Data Bundle')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h3>Data Bundle</h3>
            <p class="text-muted mb-0">
                Kelola paket bundling sebagai output dari sistem rekomendasi.
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
    <h4 class="card-title">Daftar Bundle</h4>
</div>

<div class="card-body">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="alert alert-light border">
        <strong>Catatan:</strong>
        Bundle berisi beberapa item layanan. Rule rekomendasi diatur pada menu <strong>Recommendation Rule</strong>.
        Varian/ukuran item tidak disimpan langsung di bundle, tetapi dicek dari data item variant saat paket ditampilkan.
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama Bundle</th>
                    <th>Rule Utama</th>
                    <th>Isi Item</th>
                    <th>Harga</th>
                    <th>Custom</th>
                    <th>Status</th>
                    <th style="width: 160px;">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($bundles as $index => $bundle)
                    <tr>
                        <td>{{ $index + 1 }}</td>

                        <td class="fw-semibold">
                            {{ $bundle->bundle_code ?? '-' }}
                        </td>

                        <td>
                            <div class="fw-semibold">
                                {{ $bundle->bundle_name ?? '-' }}
                            </div>

                            @if($bundle->description)
                                <small class="text-muted">
                                    {{ \Illuminate\Support\Str::limit($bundle->description, 65) }}
                                </small>
                            @endif
                        </td>

                        <td>
                            <div class="small">
                                <div>Acara: <strong>{{ $bundle->jenis_acara ?? '-' }}</strong></div>
                                <div>Adat: <strong>{{ $bundle->kategori_adat ?? '-' }}</strong></div>
                                <div>Gender: <strong>{{ $bundle->gender ?? '-' }}</strong></div>
                                <div>Rias: <strong>{{ $bundle->butuh_rias ? 'Ya' : 'Tidak' }}</strong></div>
                                <div>Budget: <strong>{{ $bundle->budget_category ?? '-' }}</strong></div>
                            </div>
                        </td>

                        <td>
                            @if($bundle->bundleItems->count())
                                <span class="badge bg-light text-dark border mb-1">
                                    {{ $bundle->bundleItems->count() }} item
                                </span>

                                <div class="small text-muted">
                                    {{ $bundle->bundleItems->take(3)->map(fn ($bundleItem) => $bundleItem->item->name ?? null)->filter()->implode(', ') }}

                                    @if($bundle->bundleItems->count() > 3)
                                        ...
                                    @endif
                                </div>
                            @else
                                <span class="badge bg-warning text-dark">Belum ada item</span>
                            @endif
                        </td>

                        <td>
                            Rp{{ number_format($bundle->price ?? 0, 0, ',', '.') }}
                        </td>

                        <td>
                            @if($bundle->is_custom)
                                <span class="badge bg-warning text-dark">Ya</span>
                            @else
                                <span class="badge bg-light text-dark border">Tidak</span>
                            @endif
                        </td>

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
                        <td colspan="9" class="text-center text-muted py-4">
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
