@extends('admin.layouts.master')
@section('title', 'Data Item Variant & Stok')

@section('content')
@php
    $summary = $stockSummary ?? [
        'total_variants' => $variants->count(),
        'active_variants' => $variants->where('is_active', true)->count(),
        'total_stock' => $variants->sum('stock'),
        'available_stock' => $variants->sum('available_stock'),
        'rented_stock' => $variants->sum(fn ($variant) => max(0, ($variant->stock ?? 0) - ($variant->available_stock ?? 0))),
        'empty_stock' => $variants->where('available_stock', '<=', 0)->count(),
    ];
@endphp

<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h3>Data Item Variant & Stok</h3>
            <p class="text-muted mb-0">
                Kelola ukuran, warna, harga, stok total, dan stok tersedia setiap produk.
            </p>
        </div>

        <a href="{{ route('item-variants.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Tambah Varian
        </a>
    </div>
</div>

<div class="page-content">
<section class="section">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <strong>Update stok gagal.</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-6 col-xl-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted d-block">Total Varian</small>
                            <h4 class="mb-0">{{ number_format($summary['total_variants'] ?? 0, 0, ',', '.') }}</h4>
                        </div>
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:44px;height:44px;">
                            <i class="bi bi-grid-3x3-gap-fill"></i>
                        </div>
                    </div>
                    <small class="text-muted">{{ number_format($summary['active_variants'] ?? 0, 0, ',', '.') }} varian aktif</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted d-block">Stok Total</small>
                            <h4 class="mb-0">{{ number_format($summary['total_stock'] ?? 0, 0, ',', '.') }}</h4>
                        </div>
                        <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center" style="width:44px;height:44px;">
                            <i class="bi bi-box-seam"></i>
                        </div>
                    </div>
                    <small class="text-muted">Seluruh stok fisik terdata</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted d-block">Stok Tersedia</small>
                            <h4 class="mb-0">{{ number_format($summary['available_stock'] ?? 0, 0, ',', '.') }}</h4>
                        </div>
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width:44px;height:44px;">
                            <i class="bi bi-check2-circle"></i>
                        </div>
                    </div>
                    <small class="text-muted">Bisa dipesan pelanggan</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted d-block">Sedang Tersewa/Dipesan</small>
                            <h4 class="mb-0">{{ number_format($summary['rented_stock'] ?? 0, 0, ',', '.') }}</h4>
                        </div>
                        <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center" style="width:44px;height:44px;">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                    </div>
                    <small class="text-muted">{{ number_format($summary['empty_stock'] ?? 0, 0, ',', '.') }} varian habis</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h4 class="card-title mb-1">Daftar Varian Item</h4>
                    <p class="text-muted mb-0">
                        Gunakan tombol <strong>Update Stok</strong> untuk menambah, mengurangi, atau mengatur ulang stok produk.
                    </p>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="alert alert-light border">
                <strong>Catatan stok:</strong>
                <ul class="mb-0 mt-2">
                    <li><strong>Stok Total</strong> adalah jumlah fisik produk yang dimiliki salon.</li>
                    <li><strong>Stok Tersedia</strong> adalah stok yang masih bisa dipesan pelanggan.</li>
                    <li>Stok otomatis berkurang saat checkout, dan otomatis kembali saat order dibatalkan atau diselesaikan.</li>
                </ul>
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
                            <th style="width: 230px;">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($variants as $index => $variant)
                            @php
                                $item = $variant->item;
                                $totalStock = (int) ($variant->stock ?? 0);
                                $availableStock = (int) ($variant->available_stock ?? 0);
                                $bookedStock = max(0, $totalStock - $availableStock);
                                $stockPercent = $totalStock > 0 ? min(100, round(($availableStock / $totalStock) * 100)) : 0;
                                $stockBadge = $availableStock <= 0 ? 'danger' : ($availableStock <= 2 ? 'warning text-dark' : 'success');
                                $stockLabel = $availableStock <= 0 ? 'Habis' : ($availableStock <= 2 ? 'Menipis' : 'Tersedia');
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

                                <td style="min-width: 190px;">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-semibold">
                                            {{ $availableStock }} tersedia
                                        </span>
                                        <span class="text-muted">
                                            / {{ $totalStock }} total
                                        </span>
                                    </div>

                                    <div class="progress mb-2" style="height: 8px;">
                                        <div class="progress-bar bg-{{ str_contains($stockBadge, 'warning') ? 'warning' : $stockBadge }}"
                                             role="progressbar"
                                             style="width: {{ $stockPercent }}%;"
                                             aria-valuenow="{{ $stockPercent }}"
                                             aria-valuemin="0"
                                             aria-valuemax="100"></div>
                                    </div>

                                    <div class="d-flex flex-wrap gap-1">
                                        <span class="badge bg-{{ $stockBadge }}">{{ $stockLabel }}</span>
                                        @if($bookedStock > 0)
                                            <span class="badge bg-info">{{ $bookedStock }} tersewa/dipesan</span>
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
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button"
                                                class="btn btn-sm btn-success"
                                                data-bs-toggle="modal"
                                                data-bs-target="#stockModal{{ $variant->id }}">
                                            <i class="bi bi-box-seam me-1"></i>Update Stok
                                        </button>

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

            @foreach($variants as $variant)
                @php
                    $item = $variant->item;
                    $totalStock = (int) ($variant->stock ?? 0);
                    $availableStock = (int) ($variant->available_stock ?? 0);
                    $bookedStock = max(0, $totalStock - $availableStock);
                @endphp

                <div class="modal fade" id="stockModal{{ $variant->id }}" tabindex="-1" aria-labelledby="stockModalLabel{{ $variant->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form action="{{ route('item-variants.updateStock', $variant->id) }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <div class="modal-header">
                                    <h5 class="modal-title" id="stockModalLabel{{ $variant->id }}">
                                        Update Stok Varian
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    <div class="alert alert-light border">
                                        <div class="fw-semibold mb-1">{{ $item->name ?? '-' }}</div>
                                        <small class="text-muted">
                                            Varian: {{ $variant->size ?? '-' }}{{ $variant->color ? ' / ' . $variant->color : '' }}<br>
                                            Stok tersedia saat ini: <strong>{{ $availableStock }}</strong> dari <strong>{{ $totalStock }}</strong> total.
                                            @if($bookedStock > 0)
                                                <br>Sedang tersewa/dipesan: <strong>{{ $bookedStock }}</strong>.
                                            @endif
                                        </small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Aksi Stok <span class="text-danger">*</span></label>
                                        <select name="stock_action" class="form-select" required>
                                            <option value="add">Tambah Stok Masuk</option>
                                            <option value="reduce">Kurangi Stok Rusak/Hilang</option>
                                            <option value="set_total">Atur Stok Total Menjadi</option>
                                            <option value="set_available">Atur Stok Tersedia Menjadi</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                                        <input type="number" name="quantity" class="form-control" min="0" value="1" required>
                                        <small class="text-muted">
                                            Jika memilih "Atur", angka ini menjadi nilai stok baru.
                                            Jika memilih "Tambah/Kurangi", angka ini menjadi jumlah perubahan stok.
                                        </small>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-save me-1"></i>Simpan Stok
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
</div>
@endsection
