@extends('admin.layouts.master')
@section('title', 'Data Item Variant')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3>Data Item Variant</h3>
            <p class="text-muted mb-0">Kelola ukuran, warna, stok, dan harga varian item.</p>
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
    <h4 class="card-title">Daftar Varian</h4>
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
                    <th>Item</th>
                    <th>Kategori</th>
                    <th>Jenis</th>
                    <th>Adat</th>
                    <th>Gender</th>
                    <th>SKU</th>
                    <th>Ukuran</th>
                    <th>Warna</th>
                    <th>Stok</th>
                    <th>Stok Tersedia</th>
                    <th>Harga Harian</th>
                    <th>Status</th>
                    <th style="width:180px;">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($variants as $index => $variant)
                    @php
                        $item = $variant->item;
                        $itemType = $item->item_type ?? null;
                    @endphp

                    <tr>
                        <td>{{ $index + 1 }}</td>

                        <td class="fw-semibold">
                            {{ $item->name ?? '-' }}
                        </td>

                        <td>
                            {{ $item->category->cat_name ?? '-' }}
                        </td>

                        <td>
                            {{ $itemType ? ucfirst(str_replace('_', ' ', $itemType)) : '-' }}
                        </td>

                        <td>
                            {{ $item->adat_category ?? '-' }}
                        </td>

                        <td>
                            {{ $item->gender ?? '-' }}
                        </td>

                        <td>
                            {{ $variant->sku_code ?? '-' }}
                        </td>

                        <td>
                            {{ $variant->size ?? '-' }}
                        </td>

                        <td>
                            {{ $variant->color ?? '-' }}
                        </td>

                        <td>
                            {{ $variant->stock ?? 0 }}
                        </td>

                        <td>
                            {{ $variant->available_stock ?? 0 }}
                        </td>

                        <td>
                            Rp{{ number_format($variant->daily_price ?? 0, 0, ',', '.') }}
                        </td>

                        <td>
                            @if($variant->is_active)
                                <span class="badge bg-success">Aktif</span>
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
                        <td colspan="14" class="text-center text-muted py-4">
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
