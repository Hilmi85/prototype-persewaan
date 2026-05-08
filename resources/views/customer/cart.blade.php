@extends('customer.layouts.master')

@section('title', 'Keranjang - Quin Salon')

@section('content')
@php
    $cartCollection = collect($cartItems ?? []);
@endphp

<section class="container-fluid page-header customer-hero py-5 mb-5">
    <div class="container py-5">
        <div class="row justify-content-center text-center">
            <div class="col-lg-9">
                <span class="badge bg-warning text-dark rounded-pill px-4 py-2 mb-3">
                    Quin Salon • Keranjang Customer
                </span>

                <h1 class="display-4 text-white fw-bold mb-3">
                    Keranjang Pesanan
                </h1>

                <p class="text-white mx-auto mb-4 max-w-760">
                    Cek kembali item, varian, jumlah, dan total pesanan sebelum melanjutkan ke checkout.
                </p>

                <div class="d-flex justify-content-center flex-wrap gap-2">
                    <a href="#cart-content" class="btn btn-dark rounded-pill px-4 py-3">
                        <i class="fa fa-arrow-down me-2"></i>Lihat Keranjang
                    </a>

                    <a href="{{ route('catalog') }}" class="btn btn-outline-light rounded-pill px-4 py-3">
                        <i class="fa fa-shirt me-2"></i>Tambah Item Lagi
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="cart-content" class="container-fluid py-5 bg-cream">
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

        @if(!empty($warning))
            <div class="alert alert-warning alert-dismissible fade show rounded-3 shadow-sm mb-4">
                <i class="fa fa-triangle-exclamation me-2"></i>{{ $warning }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($cartCollection->count())
            <div class="row g-4 align-items-start">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4 p-lg-5">
                            <div class="row g-3 align-items-center mb-4">
                                <div class="col-lg">
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2 mb-3">
                                        Daftar Item
                                    </span>

                                    <h3 class="fw-bold text-dark mb-2">
                                        Item di Keranjang
                                    </h3>

                                    <p class="text-muted mb-0">
                                        Ubah jumlah pesanan, sistem akan menyimpan perubahan secara otomatis.
                                    </p>
                                </div>

                                <div class="col-lg-auto">
                                    <span class="badge bg-dark rounded-pill px-3 py-2">
                                        {{ $cartCollection->count() }} item
                                    </span>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Item</th>
                                            <th>Varian</th>
                                            <th>Harga</th>
                                            <th class="cart-qty-col">Qty</th>
                                            <th>Total</th>
                                            <th class="cart-action-col">Aksi</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($cartCollection as $cartItem)
                                            @php
                                                $quantity = max(1, (int) ($cartItem['quantity'] ?? 1));
                                                $maxQuantity = $cartItem['max_quantity'] ?? null;
                                                $hasMaxQuantity = !is_null($maxQuantity) && $maxQuantity !== '';
                                            @endphp

                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div class="rounded-3 overflow-hidden bg-light flex-shrink-0">
                                                            <img src="{{ asset('img_item_upload/' . ($cartItem['item']->img ?? 'default.jpg')) }}"
                                                                 alt="{{ $cartItem['item']->name }}"
                                                                 width="74"
                                                                 height="74"
                                                                 class="object-fit-cover"
                                                                 onerror="this.onerror=null;this.src='{{ asset('img_item_upload/default.jpg') }}';">
                                                        </div>

                                                        <div>
                                                            <div class="fw-bold text-dark">
                                                                {{ $cartItem['item']->name }}
                                                            </div>

                                                            <small class="text-muted">
                                                                {{ $cartItem['item']->category->cat_name ?? '-' }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td>
                                                    @if($cartItem['variant'])
                                                        <div class="fw-semibold text-dark">
                                                            {{ $cartItem['variant']->size ?? '-' }}
                                                            @if($cartItem['variant']->color)
                                                                / {{ $cartItem['variant']->color }}
                                                            @endif
                                                        </div>

                                                        <small class="text-muted">
                                                            Stok tersedia: {{ $cartItem['variant']->available_stock }}
                                                        </small>
                                                    @else
                                                        <span class="badge bg-light text-dark border rounded-pill">
                                                            Tanpa varian
                                                        </span>
                                                    @endif
                                                </td>

                                                <td>
                                                    <span class="text-dark">
                                                        Rp{{ number_format($cartItem['price'], 0, ',', '.') }}
                                                    </span>
                                                </td>

                                                <td>
                                                    <form action="{{ route('cart.update', $cartItem['key']) }}"
                                                          method="POST"
                                                          class="cart-auto-update-form">
                                                        @csrf

                                                        <div class="input-group input-group-sm">
                                                            <button type="button"
                                                                    class="btn btn-outline-dark cart-qty-minus"
                                                                    aria-label="Kurangi jumlah">
                                                                <i class="fa fa-minus"></i>
                                                            </button>

                                                            <input type="number"
                                                                   name="quantity"
                                                                   min="1"
                                                                   @if($hasMaxQuantity)
                                                                       max="{{ $maxQuantity }}"
                                                                   @endif
                                                                   value="{{ $quantity }}"
                                                                   data-original-value="{{ $quantity }}"
                                                                   class="form-control text-center cart-qty-input">

                                                            <button type="button"
                                                                    class="btn btn-outline-dark cart-qty-plus"
                                                                    aria-label="Tambah jumlah">
                                                                <i class="fa fa-plus"></i>
                                                            </button>
                                                        </div>

                                                        <small class="text-muted d-block mt-1 cart-qty-status">
                                                            Otomatis tersimpan
                                                        </small>
                                                    </form>
                                                </td>

                                                <td>
                                                    <strong class="text-dark">
                                                        Rp{{ number_format($cartItem['total_price'], 0, ',', '.') }}
                                                    </strong>
                                                </td>

                                                <td>
                                                    <form action="{{ route('cart.remove', $cartItem['key']) }}"
                                                          method="POST"
                                                          onsubmit="return confirm('Hapus item ini dari keranjang?')">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill">
                                                            <i class="fa fa-trash me-1"></i>Hapus
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex flex-wrap gap-2 mt-4">
                                <a href="{{ route('catalog') }}" class="btn btn-outline-dark rounded-pill px-4">
                                    <i class="fa fa-arrow-left me-2"></i>Tambah Item Lagi
                                </a>

                                <form action="{{ route('cart.clear') }}"
                                      method="POST"
                                      onsubmit="return confirm('Kosongkan seluruh keranjang?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-outline-danger rounded-pill px-4">
                                        <i class="fa fa-trash me-2"></i>Kosongkan Keranjang
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-body p-4">
                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2 mb-3">
                                Ringkasan Belanja
                            </span>

                            <h4 class="fw-bold text-dark mb-4">
                                Total Keranjang
                            </h4>

                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Jumlah Item</span>
                                <strong class="text-dark">
                                    {{ $cartCollection->sum('quantity') }}
                                </strong>
                            </div>

                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Subtotal</span>
                                <strong class="text-dark">
                                    Rp{{ number_format($subtotal, 0, ',', '.') }}
                                </strong>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <span class="fw-semibold text-dark">Total</span>
                                <span class="fw-bold fs-4 text-dark">
                                    Rp{{ number_format($subtotal, 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="alert alert-warning rounded-4 mb-4">
                                <small>
                                    Data customer, tanggal sewa, booking, dan pembayaran akan diisi pada halaman checkout.
                                </small>
                            </div>

                            <div class="d-grid gap-2">
                                <a href="{{ route('checkout.cart.show') }}" class="btn btn-dark rounded-pill py-3">
                                    <i class="fa fa-credit-card me-2"></i>Lanjut Checkout
                                </a>

                                <a href="{{ route('catalog') }}" class="btn btn-outline-dark rounded-pill py-3">
                                    <i class="fa fa-shirt me-2"></i>Tambah Item Lagi
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2 mb-3">
                                Info Checkout
                            </span>

                            <ol class="text-muted mb-0">
                                <li>Periksa item dan varian.</li>
                                <li>Ubah jumlah jika diperlukan.</li>
                                <li>Sistem menyimpan Qty otomatis.</li>
                                <li>Lanjut checkout dan pilih pembayaran.</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body text-center p-5">
                    <div class="display-5 text-muted mb-3">
                        <i class="fa fa-cart-shopping"></i>
                    </div>

                    <h4 class="fw-bold text-dark mb-2">
                        Keranjang masih kosong
                    </h4>

                    <p class="text-muted mb-4">
                        Silakan pilih item dari katalog atau gunakan fitur rekomendasi paket.
                    </p>

                    <div class="d-flex justify-content-center flex-wrap gap-2">
                        <a href="{{ route('catalog') }}" class="btn btn-dark rounded-pill px-4 py-3">
                            <i class="fa fa-shirt me-2"></i>Ke Katalog
                        </a>

                        <a href="{{ route('recommendation.index') }}" class="btn btn-outline-dark rounded-pill px-4 py-3">
                            <i class="fa fa-gift me-2"></i>Cari Rekomendasi
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll('.cart-auto-update-form');
    const timers = new WeakMap();

    function normalizeQuantity(input) {
        let value = parseInt(input.value, 10);
        const min = parseInt(input.getAttribute('min') || '1', 10);
        const maxAttr = input.getAttribute('max');
        const max = maxAttr ? parseInt(maxAttr, 10) : null;

        if (Number.isNaN(value) || value < min) {
            value = min;
        }

        if (max !== null && value > max) {
            value = max;
        }

        input.value = value;
        return value;
    }

    function setStatus(form, message, type) {
        const status = form.querySelector('.cart-qty-status');

        if (!status) {
            return;
        }

        status.textContent = message;
        status.classList.remove('text-muted', 'text-success', 'text-danger', 'text-warning');

        if (type === 'success') {
            status.classList.add('text-success');
        } else if (type === 'danger') {
            status.classList.add('text-danger');
        } else if (type === 'warning') {
            status.classList.add('text-warning');
        } else {
            status.classList.add('text-muted');
        }
    }

    function scheduleSubmit(form, delay = 700) {
        const input = form.querySelector('.cart-qty-input');

        if (!input) {
            return;
        }

        const newValue = normalizeQuantity(input);
        const oldValue = parseInt(input.dataset.originalValue || '1', 10);

        if (newValue === oldValue) {
            setStatus(form, 'Qty belum berubah', 'muted');
            return;
        }

        if (timers.has(form)) {
            clearTimeout(timers.get(form));
        }

        setStatus(form, 'Menyimpan perubahan...', 'warning');

        const timer = setTimeout(function () {
            input.dataset.originalValue = newValue;
            form.submit();
        }, delay);

        timers.set(form, timer);
    }

    forms.forEach(function (form) {
        const input = form.querySelector('.cart-qty-input');
        const minusButton = form.querySelector('.cart-qty-minus');
        const plusButton = form.querySelector('.cart-qty-plus');

        if (!input) {
            return;
        }

        input.addEventListener('input', function () {
            scheduleSubmit(form, 900);
        });

        input.addEventListener('change', function () {
            scheduleSubmit(form, 350);
        });

        input.addEventListener('blur', function () {
            scheduleSubmit(form, 250);
        });

        if (minusButton) {
            minusButton.addEventListener('click', function () {
                const currentValue = normalizeQuantity(input);
                const min = parseInt(input.getAttribute('min') || '1', 10);

                if (currentValue <= min) {
                    setStatus(form, 'Jumlah minimal 1', 'danger');
                    input.value = min;
                    return;
                }

                input.value = currentValue - 1;
                scheduleSubmit(form, 250);
            });
        }

        if (plusButton) {
            plusButton.addEventListener('click', function () {
                const currentValue = normalizeQuantity(input);
                const maxAttr = input.getAttribute('max');
                const max = maxAttr ? parseInt(maxAttr, 10) : null;

                if (max !== null && currentValue >= max) {
                    setStatus(form, 'Jumlah melebihi stok tersedia', 'danger');
                    input.value = max;
                    return;
                }

                input.value = currentValue + 1;
                scheduleSubmit(form, 250);
            });
        }
    });
});
</script>
@endsection
