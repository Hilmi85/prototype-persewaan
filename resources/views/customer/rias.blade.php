@extends('customer.layouts.master')

@section('title', 'Jasa Rias - Quin Salon')

@section('content')
<div class="container-fluid page-header py-5 mb-5"
     style="margin-top: -55px !important; padding-top: 170px !important; background: linear-gradient(rgba(60, 42, 33, 0.65), rgba(60, 42, 33, 0.65)), url('{{ asset('img_item_upload/indo.jpg') }}'); background-position: center center; background-repeat: no-repeat; background-size: cover;">
    <div class="container py-5">
        <div class="row justify-content-center text-center">
            <div class="col-lg-9">
                <span class="badge rounded-pill px-4 py-2 mb-3"
                      style="background-color: rgba(255,255,255,0.12); color: #f5d2a6;">
                    Quin Salon • Layanan Rias
                </span>

                <h1 class="display-3 text-white fw-bold mb-3">Layanan Rias Elegan</h1>

                <p class="text-white mx-auto mb-4" style="max-width: 760px;">
                    Temukan layanan rias terbaik untuk melengkapi penampilan Anda di momen spesial.
                </p>

                <div class="d-flex justify-content-center flex-wrap gap-3">
                    <a href="#katalog-rias" class="btn rounded-pill px-4 py-3" style="background-color: #8b5e3c; color: #fff;">
                        <i class="fa fa-arrow-down me-2"></i>Lihat Layanan Rias
                    </a>

                    <a href="{{ route('recommendation.index') }}" class="btn rounded-pill px-4 py-3"
                       style="background-color: rgba(255,255,255,0.08); color: #fff; border: 1px solid rgba(255,255,255,0.35);">
                        <i class="fa fa-gift me-2"></i>Coba Rekomendasi Paket
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="katalog-rias" class="container-fluid py-5" style="background-color: #fffaf5;">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm mb-4">
                <i class="fa fa-circle-check me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm mb-4">
                <i class="fa fa-circle-exclamation me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="text-center mx-auto mb-5" style="max-width: 760px;">
            <h6 class="text-uppercase mb-2" style="color: #b88352; letter-spacing: 2px; font-weight: 700;">
                Katalog Jasa Rias
            </h6>

            <h2 class="fw-bold mb-2">Pilih Layanan Rias</h2>

            <p class="text-muted mb-0">
                Jasa rias dapat dipesan langsung atau dikombinasikan melalui paket bundling.
            </p>
        </div>

        <div class="row g-4">
            @forelse ($items as $item)
                @php
                    $availableVariants = $item->itemVariants
                        ->where('is_active', true)
                        ->where('available_stock', '>', 0);

                    $canDirectAdd = $availableVariants->count() === 0;
                @endphp

                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden" style="border: 1px solid #f1e3d3 !important;">
                        <img src="{{ asset('img_item_upload/' . ($item->img ?? 'default.jpg')) }}"
                             class="img-fluid w-100"
                             alt="{{ $item->name }}"
                             style="height: 250px; object-fit: cover;"
                             onerror="this.onerror=null;this.src='{{ asset('img_item_upload/default.jpg') }}';">

                        <div class="card-body d-flex flex-column p-4">
                            <span class="badge rounded-pill px-3 py-2 mb-3 align-self-start"
                                  style="background-color: #fff7ef; color: #8b5e3c; border: 1px solid #f0dfcf;">
                                Jasa Rias
                            </span>

                            <h4 class="fw-bold mb-2">
                                {{ $item->name }}
                            </h4>

                            <p class="text-muted mb-3">
                                {{ \Illuminate\Support\Str::limit($item->description, 120) }}
                            </p>

                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <small class="text-muted d-block">Harga</small>
                                        <div class="fw-bold" style="color: #8b5e3c;">
                                            Rp{{ number_format($item->price, 0, ',', '.') }}
                                        </div>
                                    </div>

                                    @if($availableVariants->count())
                                        <span class="badge bg-success">
                                            {{ $availableVariants->count() }} varian
                                        </span>
                                    @else
                                        <span class="badge bg-primary">
                                            Layanan tersedia
                                        </span>
                                    @endif
                                </div>

                                <div class="row g-2">
                                    <div class="col-6">
                                        <a href="{{ route('catalog.show', $item->id) }}"
                                           class="btn w-100 rounded-pill px-3 py-2"
                                           style="background-color: #8b5e3c; color: #fff;">
                                            Detail
                                        </a>
                                    </div>

                                    <div class="col-6">
                                        @if($canDirectAdd)
                                            <form action="{{ route('cart.add', $item->id) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                        class="btn w-100 rounded-pill px-3 py-2"
                                                        style="border: 1px solid #d8b892; color: #8b5e3c; background-color: #fff;">
                                                    Tambah
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('catalog.show', $item->id) }}"
                                               class="btn w-100 rounded-pill px-3 py-2"
                                               style="border: 1px solid #d8b892; color: #8b5e3c; background-color: #fff;">
                                                Pilih
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <div class="alert alert-light border rounded-4 py-5">
                        Belum ada layanan rias tersedia.
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
