@extends('customer.layouts.master')

@section('content')
<div class="container-fluid page-header py-5 mb-5"
     style="margin-top: -55px !important; padding-top: 170px !important; background: linear-gradient(rgba(60, 42, 33, 0.65), rgba(60, 42, 33, 0.65)), url('{{ asset('img_item_upload/indo.jpg') }}'); background-position: center center; background-repeat: no-repeat; background-size: cover;">
    <div class="container py-5 text-center">
        <span class="badge rounded-pill px-4 py-2 mb-3"
              style="background-color: rgba(255,255,255,0.12); color: #f5d2a6;">
            Quin Salon • Katalog
        </span>
        <h1 class="display-4 text-white fw-bold">Katalog Layanan & Item</h1>
        <p class="text-white">Temukan baju adat, aksesoris, dan jasa rias terbaik untuk acara Anda.</p>
    </div>
</div>

<div class="container-fluid py-5" style="background-color: #fffaf5;">
    <div class="container">
        <form method="GET" action="{{ route('catalog') }}" class="row g-3 mb-5">
            <div class="col-md-4">
                <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control rounded-3" placeholder="Cari item...">
            </div>
            <div class="col-md-3">
                <select name="item_type" class="form-select rounded-3">
                    <option value="">Semua Jenis</option>
                    <option value="baju_adat" {{ request('item_type') == 'baju_adat' ? 'selected' : '' }}>Baju Adat</option>
                    <option value="aksesoris" {{ request('item_type') == 'aksesoris' ? 'selected' : '' }}>Aksesoris</option>
                    <option value="jasa_rias" {{ request('item_type') == 'jasa_rias' ? 'selected' : '' }}>Jasa Rias</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="gender" class="form-select rounded-3">
                    <option value="">Semua Gender</option>
                    <option value="Laki-laki" {{ request('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="Perempuan" {{ request('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    <option value="Unisex" {{ request('gender') == 'Unisex' ? 'selected' : '' }}>Unisex</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn w-100 rounded-3" style="background-color: #8b5e3c; color: white;">Filter</button>
            </div>
        </form>
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert"
                style="border: 1px solid #d8e9c8; background-color: #f6fff0; color: #4f6f2f;">
                <i class="fa fa-circle-check me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            @forelse ($items as $item)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden" style="border: 1px solid #f1e3d3 !important;">
                        <div class="position-relative">
                            <img src="{{ asset('img_item_upload/' . ($item->img ?? 'default.jpg')) }}"
                                 class="img-fluid w-100"
                                 alt="{{ $item->name }}"
                                 style="height: 250px; object-fit: cover;"
                                 onerror="this.onerror=null;this.src='{{ asset('img_item_upload/default.jpg') }}';">
                            <span class="badge position-absolute top-0 start-0 m-3 px-3 py-2 rounded-pill"
                                  style="background-color: #8b5e3c; color: #fff;">
                                {{ $item->category->cat_name ?? 'Layanan' }}
                            </span>
                        </div>

                        <div class="card-body d-flex flex-column p-4">
                            <h4 class="fw-bold mb-2" style="min-height: 58px; color: #3f2c22;">{{ $item->name }}</h4>
                            <p class="text-muted mb-0" style="min-height: 72px;">{{ $item->description }}</p>

                            <div class="mt-auto d-flex justify-content-between align-items-center flex-wrap gap-3 pt-3 border-top">
                                <div>
                                    <small class="text-muted d-block mb-1">Harga</small>
                                    <div class="fw-bold" style="color: #8b5e3c; font-size: 1.1rem;">
                                        Rp{{ number_format($item->price, 0, ',', '.') }}
                                    </div>
                                </div>

                                <div class="row g-2" style="width: 300px;">
                                    <div class="col-6">
                                        <a href="{{ route('catalog.show', $item->id) }}"
                                           class="btn w-100 rounded-pill py-2"
                                           style="background-color: #8b5e3c; color: #fff; border: none; font-weight: 600;">
                                            <i class="fa fa-eye me-1"></i> Detail
                                        </a>
                                    </div>

                                    <div class="col-6">
                                        <form action="{{ route('cart.add', $item->id) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                    class="btn w-100 rounded-pill py-2"
                                                    style="border: 1px solid #d8b892; color: #8b5e3c; background-color: #fff; font-weight: 600;">
                                                <i class="fa fa-cart-plus me-1"></i> Tambah
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-light text-center border rounded-4 py-5">
                        Tidak ada item yang sesuai dengan filter.
                    </div>
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $items->links() }}
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    setTimeout(() => {
        const alertBox = document.querySelector('.alert.alert-success');
        if (alertBox) {
            const alert = bootstrap.Alert.getOrCreateInstance(alertBox);
            alert.close();
        }
    }, 2500);
</script>
@endsection
