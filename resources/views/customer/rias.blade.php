@extends('customer.layouts.master')

@section('title', 'Jasa Rias - Quin Salon')

@section('content')
<section class="container-fluid page-header customer-hero py-5 mb-5">
    <div class="container py-5">
        <div class="row justify-content-center text-center">
            <div class="col-lg-9">
                <span class="badge bg-warning text-dark rounded-pill px-4 py-2 mb-3">
                    Quin Salon • Layanan Rias
                </span>

                <h1 class="display-4 text-white fw-bold mb-3">
                    Layanan Rias Elegan
                </h1>

                <p class="text-white mx-auto mb-4 max-w-760">
                    Temukan layanan rias terbaik untuk melengkapi penampilan Anda di momen spesial.
                </p>

                <div class="d-flex justify-content-center flex-wrap gap-2">
                    <a href="#katalog-rias" class="btn btn-dark rounded-pill px-4 py-3">
                        <i class="fa fa-arrow-down me-2"></i>Lihat Layanan Rias
                    </a>

                    <a href="{{ route('recommendation.index') }}" class="btn btn-outline-light rounded-pill px-4 py-3">
                        <i class="fa fa-gift me-2"></i>Coba Rekomendasi Paket
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="katalog-rias" class="container-fluid py-5 bg-cream">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4">
                <i class="fa fa-circle-check me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4">
                <i class="fa fa-circle-exclamation me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row justify-content-center text-center mb-5">
            <div class="col-lg-8">
                <span class="badge bg-warning text-dark rounded-pill px-4 py-2 mb-3">
                    Katalog Jasa Rias
                </span>

                <h2 class="fw-bold text-dark mb-3">
                    Pilih Layanan Rias
                </h2>

                <p class="text-muted mb-0">
                    Jasa rias dapat dipesan langsung atau dikombinasikan melalui paket bundling.
                </p>
            </div>
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
                    <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                        <div class="ratio ratio-4x3 bg-light border-bottom overflow-hidden">
                            <img src="{{ asset('img_item_upload/' . ($item->img ?? 'default.jpg')) }}"
                                 class="w-100 h-100 object-fit-cover"
                                 alt="{{ $item->name }}"
                                 onerror="this.onerror=null;this.src='{{ asset('img_item_upload/default.jpg') }}';">
                        </div>

                        <div class="card-body d-flex flex-column p-4">
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <span class="badge bg-dark rounded-pill">
                                    Jasa Rias
                                </span>

                                @if($availableVariants->count())
                                    <span class="badge bg-success rounded-pill">
                                        {{ $availableVariants->count() }} varian
                                    </span>
                                @else
                                    <span class="badge bg-primary rounded-pill">
                                        Layanan tersedia
                                    </span>
                                @endif
                            </div>

                            <h4 class="fw-bold text-dark mb-2">
                                {{ $item->name }}
                            </h4>

                            <p class="text-muted mb-3">
                                {{ \Illuminate\Support\Str::limit($item->description, 120) }}
                            </p>

                            <div class="bg-light border rounded-3 p-3 mt-auto mb-3">
                                <small class="text-muted d-block mb-1">Harga</small>

                                <div class="fw-bold text-dark">
                                    Rp{{ number_format($item->price, 0, ',', '.') }}
                                </div>
                            </div>

                            <div class="row g-2">
                                <div class="col-6">
                                    <a href="{{ route('catalog.show', $item->id) }}" class="btn btn-dark w-100 rounded-pill">
                                        <i class="fa fa-eye me-1"></i>Detail
                                    </a>
                                </div>

                                <div class="col-6">
                                    @if($canDirectAdd)
                                        <form action="{{ route('cart.add', $item->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-dark w-100 rounded-pill">
                                                <i class="fa fa-cart-plus me-1"></i>Tambah
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('catalog.show', $item->id) }}" class="btn btn-outline-dark w-100 rounded-pill">
                                            <i class="fa fa-list me-1"></i>Pilih
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body text-center p-5">
                            <div class="display-5 text-muted mb-3">
                                <i class="fa fa-wand-magic-sparkles"></i>
                            </div>

                            <h4 class="fw-bold text-dark mb-2">
                                Belum ada layanan rias tersedia
                            </h4>

                            <p class="text-muted mb-0">
                                Silakan tambahkan layanan rias aktif melalui halaman admin.
                            </p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
