@extends('customer.layouts.master')

@section('content')
@php
    $inputJenisAcara = $validated['jenis_acara'] ?? '-';
    $inputKategoriItem = $validated['kategori_item'] ?? '-';
    $inputKategoriAdat = $validated['kategori_adat'] ?? '-';
    $inputGender = $validated['gender'] ?? '-';
    $inputButuhRias = isset($validated['butuh_rias']) && (string) $validated['butuh_rias'] === '1' ? 'Ya' : 'Tidak';
    $inputBudget = $validated['budget_category'] ?? $validated['budget'] ?? '-';

    $isCustomResult = !$bundle;

    $alternativeBundles = collect($alternativeBundles ?? []);
@endphp

<div class="container-fluid py-5" style="background-color: #fffaf5; min-height: 100vh;">
    <div class="container py-5">
        <div class="text-center mx-auto mb-5" style="max-width: 760px;">
            <h6 class="text-uppercase mb-2" style="color: #b88352; letter-spacing: 2px; font-weight: 700;">
                Hasil Rekomendasi
            </h6>
            <h2 class="fw-bold">Paket yang Direkomendasikan</h2>
            <p class="text-muted mb-0">
                Sistem mencocokkan input Anda dengan data bundle, item, dan item-varian yang tersedia.
            </p>
        </div>

        <div class="row justify-content-center mb-4">
            <div class="col-lg-10">
                <div class="bg-white rounded-4 shadow-sm p-4" style="border: 1px solid #f1e3d3;">
                    <h5 class="fw-bold mb-3">Rule yang Diproses</h5>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <small class="text-muted d-block">Jenis Acara</small>
                            <span class="fw-semibold">{{ $inputJenisAcara }}</span>
                        </div>

                        <div class="col-md-4">
                            <small class="text-muted d-block">Kategori Item</small>
                            <span class="fw-semibold">{{ $inputKategoriItem }}</span>
                        </div>

                        <div class="col-md-4">
                            <small class="text-muted d-block">Kategori Adat</small>
                            <span class="fw-semibold">{{ $inputKategoriAdat }}</span>
                        </div>

                        <div class="col-md-4">
                            <small class="text-muted d-block">Gender</small>
                            <span class="fw-semibold">{{ $inputGender }}</span>
                        </div>

                        <div class="col-md-4">
                            <small class="text-muted d-block">Butuh Rias</small>
                            <span class="fw-semibold">{{ $inputButuhRias }}</span>
                        </div>

                        <div class="col-md-4">
                            <small class="text-muted d-block">Budget</small>
                            <span class="fw-semibold">{{ $inputBudget }}</span>
                        </div>
                    </div>

                    <div class="mt-4 p-3 rounded-4" style="background-color: #fff7ef; border: 1px solid #f0dfcf; color: #7a6456;">
                        <strong>Rule-Based Logic:</strong>
                        <div class="mt-2 small">
                            IF jenis_acara = "{{ $inputJenisAcara }}"
                            AND kategori_item = "{{ $inputKategoriItem }}"
                            AND kategori_adat = "{{ $inputKategoriAdat }}"
                            AND gender = "{{ $inputGender }}"
                            AND butuh_rias = "{{ $inputButuhRias }}"
                            AND budget = "{{ $inputBudget }}"
                            THEN tampilkan paket sesuai data.
                            ELSE tampilkan Paket Custom.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($bundle)
            <div class="row justify-content-center mb-5">
                <div class="col-lg-10">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-body p-4 p-md-5">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
                                <div>
                                    <span class="badge rounded-pill px-3 py-2 mb-3" style="background-color: #8b5e3c; color: #fff;">
                                        Paket Utama
                                    </span>

                                    <h3 class="fw-bold mb-2">
                                        {{ $bundle->bundle_name ?? 'Paket Rekomendasi' }}
                                    </h3>

                                    <p class="text-muted mb-0">
                                        {{ $bundle->description ?? 'Paket ini dipilih berdasarkan kecocokan rule dengan data yang tersedia.' }}
                                    </p>
                                </div>

                                <div class="text-md-end">
                                    <small class="text-muted d-block">Kode Bundle</small>
                                    <span class="fw-semibold">
                                        {{ $bundle->bundle_code ?? '-' }}
                                    </span>
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <div class="p-3 rounded-4 h-100" style="background-color: #fffaf5; border: 1px solid #f1e3d3;">
                                        <small class="text-muted d-block">Jenis Acara</small>
                                        <span class="fw-semibold">{{ $bundle->jenis_acara ?? '-' }}</span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="p-3 rounded-4 h-100" style="background-color: #fffaf5; border: 1px solid #f1e3d3;">
                                        <small class="text-muted d-block">Kategori Adat</small>
                                        <span class="fw-semibold">{{ $bundle->kategori_adat ?? '-' }}</span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="p-3 rounded-4 h-100" style="background-color: #fffaf5; border: 1px solid #f1e3d3;">
                                        <small class="text-muted d-block">Gender</small>
                                        <span class="fw-semibold">{{ $bundle->gender ?? '-' }}</span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="p-3 rounded-4 h-100" style="background-color: #fffaf5; border: 1px solid #f1e3d3;">
                                        <small class="text-muted d-block">Butuh Rias</small>
                                        <span class="fw-semibold">
                                            {{ $bundle->butuh_rias ? 'Ya' : 'Tidak' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="p-3 rounded-4 h-100" style="background-color: #fffaf5; border: 1px solid #f1e3d3;">
                                        <small class="text-muted d-block">Budget</small>
                                        <span class="fw-semibold">{{ $bundle->budget_category ?? '-' }}</span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="p-3 rounded-4 h-100" style="background-color: #fffaf5; border: 1px solid #f1e3d3;">
                                        <small class="text-muted d-block">Status Output</small>
                                        <span class="badge bg-success">Sesuai Data</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h5 class="fw-bold mb-3">Isi Paket Berdasarkan Data Item</h5>

                                @if($bundle->bundleItems && $bundle->bundleItems->count())
                                    <div class="table-responsive">
                                        <table class="table table-bordered align-middle mb-0">
                                            <thead style="background-color: #fff7ef;">
                                                <tr>
                                                    <th>Item</th>
                                                    <th>Kategori</th>
                                                    <th>Adat</th>
                                                    <th>Gender</th>
                                                    <th>Jumlah</th>
                                                    <th>Harga Item</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach ($bundle->bundleItems as $bundleItem)
                                                    @php
                                                        $item = $bundleItem->item ?? null;
                                                        $categoryName = $item->category->cat_name ?? '-';
                                                        $itemPrice = $item->price ?? 0;
                                                    @endphp

                                                    <tr>
                                                        <td class="fw-semibold">
                                                            {{ $item->name ?? '-' }}
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
                                                            Rp{{ number_format($itemPrice, 0, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-warning mb-0">
                                        Data item pada bundle ini belum tersedia.
                                    </div>
                                @endif
                            </div>

                            <div class="p-3 rounded-4 mb-4" style="background-color: #fff7ef; border: 1px solid #f0dfcf; color: #7a6456;">
                                <strong>Hasil Rule:</strong>
                                <div class="mt-2 small">
                                    Data input customer cocok dengan paket
                                    <strong>{{ $bundle->bundle_name ?? 'Paket Rekomendasi' }}</strong>,
                                    sehingga sistem menampilkan paket ini sebagai rekomendasi utama.
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <div>
                                    <small class="text-muted d-block">Estimasi Harga Bundle</small>
                                    <div class="fw-bold fs-4" style="color: #8b5e3c;">
                                        Rp{{ number_format($bundle->price ?? 0, 0, ',', '.') }}
                                    </div>
                                </div>

                                <a href="{{ route('checkout.bundle.show', $bundle->id) }}"
                                   class="btn rounded-pill px-4 py-3"
                                   style="background-color: #8b5e3c; color: #fff;">
                                    Lanjut Checkout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8">
                    <div class="text-center py-5 bg-white rounded-4 shadow-sm" style="border: 1px solid #f1e3d3;">
                        <i class="fa fa-box-open mb-3" style="font-size: 52px; color: #b88352;"></i>

                        <span class="badge rounded-pill px-3 py-2 mb-3 bg-warning text-dark">
                            Paket Custom
                        </span>

                        <h4 class="fw-bold mb-2">Belum Ada Paket yang Cocok</h4>

                        <p class="text-muted mb-4">
                            Tidak ada bundle yang cocok dengan rule dan data yang tersedia.
                            Sistem mengarahkan Anda ke Paket Custom.
                        </p>

                        <div class="mx-auto text-start p-3 rounded-4 mb-4"
                             style="max-width: 620px; background-color: #fff7ef; border: 1px solid #f0dfcf; color: #7a6456;">
                            <strong>ELSE Rule:</strong>
                            <div class="mt-2 small">
                                Karena tidak ditemukan bundle dengan kombinasi
                                jenis acara, kategori item, kategori adat, gender, kebutuhan rias, dan budget tersebut,
                                output sistem menjadi <strong>Paket Custom</strong>.
                            </div>
                        </div>

                        <a href="{{ route('recommendation.index') }}"
                           class="btn rounded-pill px-4 py-3"
                           style="background-color: #8b5e3c; color: #fff;">
                            Ubah Kriteria
                        </a>
                    </div>
                </div>
            </div>
        @endif

        @if ($alternativeBundles->count())
            <div class="text-center mx-auto mb-4" style="max-width: 760px;">
                <h4 class="fw-bold">Alternatif Paket</h4>
                <p class="text-muted mb-0">
                    Paket lain yang masih mendekati data rekomendasi Anda.
                </p>
            </div>

            <div class="row g-4">
                @foreach ($alternativeBundles as $altBundle)
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 h-100">
                            <div class="card-body p-4 d-flex flex-column">
                                <span class="badge rounded-pill px-3 py-2 mb-3 align-self-start"
                                      style="background-color: #fff7ef; color: #8b5e3c; border: 1px solid #f0dfcf;">
                                    Alternatif
                                </span>

                                <h5 class="fw-bold mb-2">
                                    {{ $altBundle->bundle_name ?? '-' }}
                                </h5>

                                <p class="text-muted mb-3">
                                    {{ $altBundle->description ?? 'Paket alternatif berdasarkan data yang tersedia.' }}
                                </p>

                                <div class="small text-muted mb-3">
                                    <div>Jenis Acara: {{ $altBundle->jenis_acara ?? '-' }}</div>
                                    <div>Adat: {{ $altBundle->kategori_adat ?? '-' }}</div>
                                    <div>Gender: {{ $altBundle->gender ?? '-' }}</div>
                                    <div>Budget: {{ $altBundle->budget_category ?? '-' }}</div>
                                </div>

                                <div class="mt-auto d-flex justify-content-between align-items-center">
                                    <div class="fw-bold" style="color: #8b5e3c;">
                                        Rp{{ number_format($altBundle->price ?? 0, 0, ',', '.') }}
                                    </div>

                                    <a href="{{ route('bundle.show', $altBundle->id) }}"
                                       class="btn rounded-pill px-3 py-2"
                                       style="background-color: #8b5e3c; color: #fff;">
                                        Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="text-center mt-5">
            <a href="{{ route('recommendation.index') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2">
                Coba Lagi
            </a>
        </div>
    </div>
</div>
@endsection
