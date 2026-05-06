@extends('admin.layouts.master')
@section('title', 'Data Item')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h3>Data Item</h3>
            <p class="text-muted mb-0">
                Kelola item baju adat, aksesoris, dan jasa rias pada sistem Quin Salon.
            </p>
        </div>
        <div>
            <a href="{{ route('items.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>Tambah Item
            </a>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Daftar Item</h4>
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
                                <th>Gambar</th>
                                <th>Nama Item</th>
                                <th>Kategori</th>
                                <th>Jenis</th>
                                <th>Adat</th>
                                <th>Gender</th>
                                <th>Harga</th>
                                <th>Status</th>
                                <th style="width: 180px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <img src="{{ asset('img_item_upload/' . ($item->img ?? 'default.jpg')) }}"
                                             alt="{{ $item->name }}"
                                             width="60"
                                             height="60"
                                             style="object-fit: cover; border-radius: 10px;"
                                             onerror="this.onerror=null;this.src='{{ asset('img_item_upload/default.jpg') }}';">
                                    </td>
                                    <td class="fw-semibold">{{ $item->name }}</td>
                                    <td>{{ $item->category->cat_name ?? '-' }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $item->item_type)) }}</td>
                                    <td>{{ $item->adat_category ?? '-' }}</td>
                                    <td>{{ $item->gender ?? '-' }}</td>
                                    <td>Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td>
                                        @if($item->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-2">
                                            <a href="{{ route('items.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            <form action="{{ route('items.updateStatus', $item->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-info text-white">
                                                    <i class="bi bi-arrow-repeat"></i>
                                                </button>
                                            </form>

                                            <form action="{{ route('items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus item ini?')">
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
                                    <td colspan="10" class="text-center text-muted py-4">
                                        Belum ada data item.
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
