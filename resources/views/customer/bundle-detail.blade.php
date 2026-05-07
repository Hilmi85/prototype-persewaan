@extends('customer.layouts.master')

@section('title', 'Detail Paket - Quin Salon')

@section('content')
@php
    $bundleItems = $bundle->bundleItems ?? collect();
@endphp

<div class="container-fluid page-header py-5 mb-5"
     style="margin-top: -55px !important; padding-top: 170px !important; background: linear-gradient(rgba(60, 42, 33, 0.65), rgba(60, 42, 33, 0.65)), url('{{ asset('img_item_upload/indo.jpg') }}'); background-position: center center; background-repeat: no-repeat; background-size: cover;">
    <div class="container py-5 text-center">
        <span class="badge rounded-pill px-4 py-2 mb-3"
              style="background-color: rgba(255,255,255,0.12); color: #f5d2a6;">
            Detail Paket Bundling
        </span>

        <h1 class="display-4 text-white fw-bold">
            {{ $bundle->bundle_name }}
        </h1>

        <p class="text-white mb-0">
            Lihat detail isi paket sebelum melanjutkan checkout.
        </p>
    </div>
</div>

<div class="container-fluid py-5" style="background-color: #fffaf5;">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="bg-white rounded-4 shadow-sm p-4 p-md-5 mb-4" style="border: 1px solid #f1e3d3;">
                    <span class="badge rounded-pill px-3 py-2 mb-3"
                          style="background-color: #fff7ef; color: #8b5e3c; border: 1px solid #f0dfcf;">
                        {{ $bundle->is_custom ? 'Paket Custom' : 'Paket Rekomendasi' }}
                    </span>

                    <h2 class="fw-bold mb-3" style="color: #3c2a21;">
                        {{ $bundle->bundle_name }}
                    </h2>

                    <p class="text-muted">
                        {{ $bundle->description ?: 'Paket bundling ini berisi kombinasi layanan yang dapat dipilih customer sesuai kebutuhan acara.' }}
                    </p>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="p-3 rounded-4 h-100" style="background-color: #fffaf5; border: 1px solid #f1e3d3;">
                                <small class="text-muted d-block">Kode Bundle</small>
                                <span class="fw-semibold">{{ $bundle->bundle_code ?? '-' }}</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 rounded-4 h-100" style="background-color: #fffaf5; border: 1px solid #f1e3d3;">
                                <small class="text-muted d-block">Jenis Acara</small>
                                <span class="fw-semibold">{{ $bundle->jenis_acara ?? '-' }}</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 rounded-4 h-100" style="background-color: #fffaf5; border: 1px solid #f1e3d3;">
                                <small class="text-muted d-block">Kategori Adat</small>
                                <span class="fw-semibold">{{ $bundle->kategori_adat ?? '-' }}</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 rounded-4 h-100" style="background-color: #fffaf5; border: 1px solid #f1e3d3;">
                                <small class="text-muted d-block">Gender</small>
                                <span class="fw-semibold">{{ $bundle->gender ?? '-' }}</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 rounded-4 h-100" style="background-color: #fffaf5; border: 1px solid #f1e3d3;">
                                <small class="text-muted d-block">Butuh Rias</small>
                                <span class="fw-semibold">{{ $bundle->butuh_rias ? 'Ya' : 'Tidak' }}</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 rounded-4 h-100" style="background-color: #fffaf5; border: 1px solid #f1e3d3;">
                                <small class="text-muted d-block">Budget</small>
                                <span class="fw-semibold">{{ $bundle->budget_category ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-3 rounded-4" style="background-color: #fff7ef; border: 1px solid #f0dfcf; color: #7a6456;">
                        <strong>Catatan Sistem:</strong>
                        <div class="small mt-2">
                            Paket ini dipakai sebagai output rekomendasi. Pengaturan kecocokan input customer tetap dilakukan melalui data Recommendation Rule oleh admin.
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-4 shadow-sm p-4 p-md-5" style="border: 1px solid #f1e3d3;">
                    <h4 class="fw-bold mb-3" style="color: #8b5e3c;">
                        Isi Paket
                    </h4>

                    @if($bundleItems->count())
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0">
                                <thead style="background-color: #fff7ef;">
                                    <tr>
                                        <th>Item</th>
                                        <th>Kategori</th>
                                        <th>Jenis</th>
                                        <th>Qty</th>
                                        <th>Varian Tersedia</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($bundleItems as $bundleItem)
                                        @php
                                            $item = $bundleItem->item;
                                            $availableVariants = $item
                                                ? $item->itemVariants
                                                    ->where('is_active', true)
                                                    ->where('available_stock', '>', 0)
                                                : collect();
                                        @endphp

                                        <tr>
                                            <td>
                                                <div class="fw-semibold">
                                                    {{ $item->name ?? '-' }}
                                                </div>

                                                @if($item?->description)
                                                    <small class="text-muted">
                                                        {{ \Illuminate\Support\Str::limit($item->description, 70) }}
                                                    </small>
                                                @endif
                                            </td>

                                            <td>{{ $item->category->cat_name ?? '-' }}</td>
                                            <td>{{ str_replace('_', ' ', $item->item_type ?? '-') }}</td>
                                            <td>{{ $bundleItem->quantity ?? 1 }}</td>

                                            <td>
                                                @if($availableVariants->count())
                                                    @foreach($availableVariants->take(3) as $variant)
                                                        <span class="badge bg-light text-dark border mb-1">
                                                            {{ $variant->size ?? '-' }} / {{ $variant->color ?? '-' }}
                                                            • stok {{ $variant->available_stock }}
                                                        </span>
                                                    @endforeach

                                                    @if($availableVariants->count() > 3)
                                                        <span class="badge bg-light text-dark border">
                                                            +{{ $availableVariants->count() - 3 }} varian
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="text-muted small">Belum ada varian tersedia.</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if($availableVariants->count())
                                                    <span class="badge bg-success">Tersedia</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Perlu Konfirmasi</span>
                                                @endif

                                                @if(!$bundleItem->is_required)
                                                    <span class="badge bg-secondary">Opsional</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-warning rounded-4 mb-0">
                            Isi paket belum diatur oleh admin.
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-5">
                <div class="bg-white rounded-4 shadow-sm p-4 mb-4" style="border: 1px solid #f1e3d3;">
                    <h4 class="fw-bold mb-3" style="color: #8b5e3c;">
                        Ringkasan Harga
                    </h4>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Harga Paket</span>
                        <span class="fw-bold fs-4" style="color: #8b5e3c;">
                            Rp{{ number_format($bundle->price ?? 0, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="p-3 rounded-4 mb-4" style="background-color: #fff7ef; border: 1px solid #f0dfcf;">
                        <small class="text-muted d-block mb-1">Informasi</small>
                        <span style="color: #6f4e37;">
                            Harga checkout mengikuti harga bundle. Harga item di dalam paket hanya sebagai data pendukung.
                        </span>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('checkout.bundle.show', $bundle->id) }}"
                           class="btn rounded-pill px-4 py-3"
                           style="background-color: #8b5e3c; color: #fff; border: none;">
                            <i class="fa fa-cart-shopping me-2"></i>Lanjut Checkout
                        </a>

                        <a href="{{ route('recommendation.index') }}"
                           class="btn btn-outline-secondary rounded-pill px-4 py-3">
                            <i class="fa fa-arrow-left me-2"></i>Kembali ke Rekomendasi
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-4 shadow-sm p-4" style="border: 1px solid #f1e3d3;">
                    <h5 class="fw-bold mb-3" style="color: #8b5e3c;">
                        Alur Sistem
                    </h5>

                    <ol class="mb-0 text-muted">
                        <li>Customer mengisi form rekomendasi.</li>
                        <li>Sistem mencocokkan input dengan rule aktif.</li>
                        <li>Rule menghasilkan bundle yang sesuai.</li>
                        <li>Detail bundle menampilkan isi item dan varian tersedia.</li>
                        <li>Customer lanjut checkout dan membuat booking.</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
