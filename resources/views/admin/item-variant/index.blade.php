@extends('admin.layouts.master')
@section('title', 'Data Item Variant')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h3>Data Item Variant</h3>
            <p class="text-muted mb-0">
                Kelola ukuran, warna, stok, dan harga varian item.
            </p>
        </div>

        <a href="{{ route('item-variants.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Tambah Varian
        </a>
    </div>
</div>

<div class="page-content">
<section class="section">
<div class="card">
<div class="card-header">
    <h4 class="card-title">Daftar Varian Item</h4>
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
        Varian dipakai untuk mengecek ukuran, warna, dan stok tersedia setelah bundle direkomendasikan.
        Varian tidak menjadi kondisi utama pada rule rekomendasi.
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Item</th>
                    <th>Varian</th>
                    <th>Stok</th>
                    <th>Harga Harian</th>
                    <th>Status</th>
                    <th style="width: 150px;">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($variants as $index => $variant)
                    @php
                        $item = $variant->item;
                    @endphp

                    <tr>
                        <td>{{ $index + 1 }}</td>

                        <td>
                            <div class="fw-semibold">
                                {{ $item->name ?? '-' }}
                            </div>

                            <small class="text-muted">
                                {{ $item->category->cat_name ?? '-' }}
                                @if($item?->item_type)
                                    • {{ ucfirst(str_replace('_', ' ', $item->item_type)) }}
                                @endif
                            </small>
                        </td>

                        <td>
                            <div class="fw-semibold">
                                {{ $variant->size ?? '-' }}
                                @if($variant->color)
                                    / {{ $variant->color }}
                                @endif
                            </div>

                            <small class="text-muted">
                                SKU: {{ $variant->sku_code ?? '-' }}
                            </small>
                        </td>

                        <td>
                            <span class="fw-semibold">
                                {{ $variant->available_stock ?? 0 }}
                            </span>
                            <span class="text-muted">
                                / {{ $variant->stock ?? 0 }}
                            </span>

                            <div>
                                @if(($variant->available_stock ?? 0) > 0)
                                    <span class="badge bg-success">Tersedia</span>
                                @else
                                    <span class="badge bg-warning text-dark">Habis / Perlu Konfirmasi</span>
                                @endif
                            </div>
                        </td>

                        <td>
                            Rp{{ number_format($variant->daily_price ?? 0, 0, ',', '.') }}
                        </td>

                        <td>
                            @if($variant->is_active)
                                <span class="badge bg-primary">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>

                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('item-variants.edit', $variant->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form action="{{ route('item-variants.destroy', $variant->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus varian ini?')">
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
                        <td colspan="7" class="text-center text-muted py-4">
                            Belum ada varian item.
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
