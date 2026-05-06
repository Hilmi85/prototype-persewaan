@extends('customer.layouts.master')

@section('title', ($bundle->bundle_name ?? 'Detail Paket') . ' - Quin Salon')

@section('content')
@php
    /*
        Detail bundle ini mengikuti sistem rule-based baru:

        IF jenis_acara
        AND kategori_item dari Data Item
        AND kategori_adat
        AND gender
        AND butuh_rias
        AND budget_category
        THEN tampilkan bundle ini
        ELSE Paket Custom

        Catatan:
        kategori_item tidak wajib ada di tabel bundles.
        kategori_item diambil dari relasi:
        bundle -> bundleItems -> item -> category -> cat_name
    */

    $bundleItems = collect($bundle->bundleItems ?? []);

    $kategoriItemList = $bundleItems
        ->map(function ($bundleItem) {
            return $bundleItem->item->category->cat_name ?? null;
        })
        ->filter()
        ->unique()
        ->values();

    $kategoriItemText = $kategoriItemList->count()
        ? $kategoriItemList->implode(', ')
        : '-';

    $butuhRiasText = $bundle->butuh_rias ? 'Ya' : 'Tidak';

    $jenisAcara = $bundle->jenis_acara ?? '-';
    $kategoriAdat = $bundle->kategori_adat ?? '-';
    $gender = $bundle->gender ?? '-';
    $budget = $bundle->budget_category ?? '-';
@endphp

<div class="container-fluid page-header py-5 mb-5"
     style="margin-top: -55px !important; padding-top: 170px !important; background: linear-gradient(rgba(60, 42, 33, 0.68), rgba(60, 42, 33, 0.68)), url('{{ asset('img_item_upload/indo.jpg') }}'); background-position: center center; background-repeat: no-repeat; background-size: cover;">
    <div class="container py-5 text-center">
        <span class="badge rounded-pill px-4 py-2 mb-3"
              style="background-color: rgba(255,255,255,0.12); color: #f5d2a6;">
            Detail Paket Bundling
        </span>

        <h1 class="display-4 text-white fw-bold">
            {{ $bundle->bundle_name ?? 'Detail Paket' }}
        </h1>

        <p class="text-white mb-0">
            Lihat rincian paket berdasarkan data bundle, Data Item, dan Item-Varian yang tersedia.
        </p>
    </div>
</div>

<div class="container-fluid py-5" style="background-color: #fffaf5;">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-7">
                <div class="bg-white rounded-4 shadow-sm p-4 p-md-5 h-100" style="border: 1px solid #f1e3d3;">
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        @if($bundle->jenis_acara)
                            <span class="badge rounded-pill px-3 py-2" style="background-color: #f8efe5; color: #8b5e3c;">
                                Acara: {{ $bundle->jenis_acara }}
                            </span>
                        @endif

                        @if($kategoriItemText !== '-')
                            <span class="badge rounded-pill px-3 py-2" style="background-color: #f8efe5; color: #8b5e3c;">
                                Kategori Item: {{ $kategoriItemText }}
                            </span>
                        @endif

                        @if($bundle->kategori_adat)
                            <span class="badge rounded-pill px-3 py-2" style="background-color: #f8efe5; color: #8b5e3c;">
                                Adat: {{ $bundle->kategori_adat }}
                            </span>
                        @endif

                        @if($bundle->gender)
                            <span class="badge rounded-pill px-3 py-2" style="background-color: #f8efe5; color: #8b5e3c;">
                                Gender: {{ $bundle->gender }}
                            </span>
                        @endif

                        @if($bundle->budget_category)
                            <span class="badge rounded-pill px-3 py-2" style="background-color: #f8efe5; color: #8b5e3c;">
                                Budget: {{ $bundle->budget_category }}
                            </span>
                        @endif

                        <span class="badge rounded-pill px-3 py-2"
                              style="background-color: {{ $bundle->butuh_rias ? '#8b5e3c' : '#d8b892' }}; color: #fff;">
                            {{ $bundle->butuh_rias ? 'Termasuk Rias' : 'Tanpa Rias' }}
                        </span>
                    </div>

                    <h2 class="fw-bold mb-3" style="color: #3f2c22;">
                        {{ $bundle->bundle_name ?? 'Paket Bundling' }}
                    </h2>

                    <p class="text-muted mb-4" style="line-height: 1.8;">
                        {{ $bundle->description ?: 'Paket bundling ini disusun berdasarkan aturan rekomendasi dan data item yang tersedia.' }}
                    </p>

                    <div class="p-3 rounded-4 mb-4" style="background-color: #fff7ef; border: 1px solid #f0dfcf; color: #7a6456;">
                        <strong>Rule-Based Logic:</strong>

                        <div class="mt-2 small" style="line-height: 1.8;">
                            IF jenis_acara = <strong>"{{ $jenisAcara }}"</strong><br>
                            AND kategori_item = <strong>"{{ $kategoriItemText }}"</strong><br>
                            AND kategori_adat = <strong>"{{ $kategoriAdat }}"</strong><br>
                            AND gender = <strong>"{{ $gender }}"</strong><br>
                            AND butuh_rias = <strong>"{{ $butuhRiasText }}"</strong><br>
                            AND budget = <strong>"{{ $budget }}"</strong><br>
                            THEN tampilkan <strong>"{{ $bundle->bundle_name ?? 'Bundle ini' }}"</strong><br>
                            ELSE tampilkan <strong>"Paket Custom"</strong>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h4 class="fw-bold mb-3" style="color: #8b5e3c;">
                            Isi Paket Berdasarkan Data Item
                        </h4>

                        @if($bundleItems->count())
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th>Nama Item</th>
                                            <th>Kategori</th>
                                            <th>Adat</th>
                                            <th>Gender</th>
                                            <th>Jumlah</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($bundleItems as $bundleItem)
                                            @php
                                                $item = $bundleItem->item ?? null;
                                                $categoryName = $item->category->cat_name ?? '-';
                                            @endphp

                                            <tr>
                                                <td>
                                                    <div class="fw-semibold">
                                                        {{ $item->name ?? '-' }}
                                                    </div>

                                                    @if(!empty($item?->description))
                                                        <small class="text-muted">
                                                            {{ $item->description }}
                                                        </small>
                                                    @endif
                                                </td>

                                                <td>
                                                    {{ $categoryName }}
                                                </td>

                                                <td>
                                                    {{ $item->adat_category ?? '-' }}
                                                </td>

                                                <td>
                                                    {{ $item->gender ?? '-' }}
                                                </td>

                                                <td>
                                                    {{ $bundleItem->quantity ?? 1 }}
                                                </td>

                                                <td>
                                                    @if($bundleItem->is_required)
                                                        <span class="badge bg-success">Wajib</span>
                                                    @else
                                                        <span class="badge bg-secondary">Opsional</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-light border rounded-4">
                                Belum ada item yang terdaftar pada paket ini.
                            </div>
                        @endif
                    </div>

                    <div class="mb-4">
                        <h4 class="fw-bold mb-3" style="color: #8b5e3c;">
                            Kesesuaian Data
                        </h4>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="p-3 rounded-4 h-100" style="background-color: #fffaf5; border: 1px solid #f1e3d3;">
                                    <small class="text-muted d-block">Sumber Kategori Item</small>
                                    <span class="fw-semibold">
                                        Data Item
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 rounded-4 h-100" style="background-color: #fffaf5; border: 1px solid #f1e3d3;">
                                    <small class="text-muted d-block">Sumber Rule</small>
                                    <span class="fw-semibold">
                                        Bundle / Recommendation Rule
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 rounded-4 h-100" style="background-color: #fffaf5; border: 1px solid #f1e3d3;">
                                    <small class="text-muted d-block">Sumber Harga</small>
                                    <span class="fw-semibold">
                                        Harga Bundle
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="p-3 rounded-4 h-100" style="background-color: #fffaf5; border: 1px solid #f1e3d3;">
                                    <small class="text-muted d-block">Status Output</small>
                                    @if($bundle->is_active)
                                        <span class="badge bg-success">Aktif dan Sesuai Data</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('checkout.bundle.show', $bundle->id) }}"
                           class="btn rounded-pill px-4 py-3"
                           style="background-color: #8b5e3c; color: #fff; border: none;">
                            <i class="fa fa-cart-shopping me-2"></i>Lanjut Checkout
                        </a>

                        <a href="{{ route('recommendation.index') }}"
                           class="btn rounded-pill px-4 py-3"
                           style="border: 1px solid #d8b892; color: #8b5e3c; background-color: #fff;">
                            <i class="fa fa-arrow-left me-2"></i>Kembali ke Rekomendasi
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="bg-white rounded-4 shadow-sm p-4 mb-4" style="border: 1px solid #f1e3d3;">
                    <h4 class="fw-bold mb-3" style="color: #8b5e3c;">
                        Ringkasan Harga
                    </h4>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Harga Paket</span>

                        <span class="fw-bold fs-5" style="color: #8b5e3c;">
                            Rp{{ number_format($bundle->price ?? 0, 0, ',', '.') }}
                        </span>
                    </div>

                    @if($bundleItems->count())
                        <div class="mb-3">
                            <small class="text-muted d-block mb-2">
                                Rincian item dalam paket
                            </small>

                            <ul class="list-unstyled mb-0">
                                @foreach($bundleItems as $bundleItem)
                                    @php
                                        $item = $bundleItem->item ?? null;
                                        $itemPrice = $item->price ?? 0;
                                    @endphp

                                    <li class="d-flex justify-content-between gap-3 mb-2">
                                        <span class="text-muted">
                                            {{ $item->name ?? '-' }} x {{ $bundleItem->quantity ?? 1 }}
                                        </span>

                                        <span class="fw-semibold">
                                            Rp{{ number_format($itemPrice, 0, ',', '.') }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="p-3 rounded-4" style="background-color: #fff7ef; border: 1px solid #f0dfcf;">
                        <small class="text-muted d-block mb-1">Catatan</small>

                        <span style="color: #6f4e37;">
                            Harga checkout mengikuti harga bundle. Harga item hanya ditampilkan sebagai informasi data pendukung.
                        </span>
                    </div>
                </div>

                <div class="bg-white rounded-4 shadow-sm p-4 mb-4" style="border: 1px solid #f1e3d3;">
                    <h4 class="fw-bold mb-3" style="color: #8b5e3c;">
                        Detail Rule Paket
                    </h4>

                    <ul class="mb-0 ps-3 text-muted" style="line-height: 1.9;">
                        <li>Jenis acara: <strong>{{ $jenisAcara }}</strong></li>
                        <li>Kategori item: <strong>{{ $kategoriItemText }}</strong></li>
                        <li>Kategori adat: <strong>{{ $kategoriAdat }}</strong></li>
                        <li>Gender: <strong>{{ $gender }}</strong></li>
                        <li>Butuh rias: <strong>{{ $butuhRiasText }}</strong></li>
                        <li>Budget: <strong>{{ $budget }}</strong></li>
                    </ul>
                </div>

                <div class="bg-white rounded-4 shadow-sm p-4" style="border: 1px solid #f1e3d3;">
                    <h4 class="fw-bold mb-3" style="color: #8b5e3c;">
                        Informasi Pemesanan
                    </h4>

                    <ul class="mb-0 ps-3 text-muted" style="line-height: 1.9;">
                        <li>Pilih paket yang sesuai dengan kebutuhan acara Anda.</li>
                        <li>Lanjutkan ke halaman checkout untuk mengisi data pemesanan.</li>
                        <li>Admin akan memproses order, booking, dan konfirmasi pembayaran.</li>
                        <li>Untuk permintaan khusus, Anda tetap bisa menghubungi admin.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
