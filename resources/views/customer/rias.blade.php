@extends('customer.layouts.master')

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

                    <a href="{{ route('home') }}" class="btn rounded-pill px-4 py-3"
                       style="background-color: rgba(255,255,255,0.08); color: #fff; border: 1px solid rgba(255,255,255,0.35);">
                        <i class="fa fa-house me-2"></i>Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="katalog-rias" class="container-fluid py-5" style="background-color: #fffaf5;">
    <div class="container">
        <div class="row g-4">
            @forelse ($items as $item)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                        <img src="{{ asset('img_item_upload/' . ($item->img ?? 'default.jpg')) }}"
                             class="img-fluid w-100"
                             alt="{{ $item->name }}"
                             style="height: 250px; object-fit: cover;"
                             onerror="this.onerror=null;this.src='{{ asset('img_item_upload/default.jpg') }}';">

                        <div class="card-body d-flex flex-column p-4">
                            <h4 class="fw-bold mb-2">{{ $item->name }}</h4>
                            <p class="text-muted mb-3">{{ $item->description }}</p>

                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <div class="fw-bold" style="color: #8b5e3c;">
                                    Rp{{ number_format($item->price, 0, ',', '.') }}
                                </div>
                                <a href="{{ route('catalog.show', $item->id) }}"
                                   class="btn rounded-pill px-4 py-2"
                                   style="background-color: #8b5e3c; color: #fff;">
                                    Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <div class="alert alert-light border rounded-4 py-5">Belum ada layanan rias tersedia.</div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
