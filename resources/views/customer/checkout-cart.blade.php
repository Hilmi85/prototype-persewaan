@extends('customer.layouts.master')

@section('title', 'Checkout Keranjang - Quin Salon')

@section('content')
@php
    $rentalDates = $rentalDates ?? session('rental_dates');
    $rentalStartValue = old('rental_start', $rentalDates['rental_start'] ?? '');
    $rentalEndValue = old('rental_end', $rentalDates['rental_end'] ?? '');
    $eventDateValue = old('event_date', $rentalStartValue);

    $rentalTermsService = app(\App\Services\RentalTermsService::class);
    $rentalTerms = $rentalTermsService->rules();
@endphp

<section class="container-fluid page-header customer-hero py-5 mb-5">
    <div class="container py-5">
        <div class="row justify-content-center text-center">
            <div class="col-lg-9">
                <span class="badge bg-warning text-dark rounded-pill px-4 py-2 mb-3">
                    Quin Salon • Checkout Keranjang
                </span>

                <h1 class="display-4 text-white fw-bold mb-3">
                    Lengkapi Data Pesanan
                </h1>

                <p class="text-white mx-auto mb-4 max-w-760">
                    Isi data customer, jadwal sewa, dan metode pembayaran sebelum pesanan diproses oleh Quin Salon.
                </p>

                <div class="d-flex justify-content-center flex-wrap gap-2">
                    <a href="#checkout-cart-form" class="btn btn-dark rounded-pill px-4 py-3">
                        <i class="fa fa-arrow-down me-2"></i>Isi Checkout
                    </a>

                    <a href="{{ route('cart.index') }}" class="btn btn-outline-light rounded-pill px-4 py-3">
                        <i class="fa fa-cart-shopping me-2"></i>Kembali ke Keranjang
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="checkout-cart-form" class="container-fluid py-5 bg-cream">
    <div class="container">
        @if($errors->any())
            <div class="alert alert-danger rounded-4 shadow-sm mb-4">
                <strong>Data belum valid.</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger rounded-4 shadow-sm mb-4">
                <i class="fa fa-circle-exclamation me-2"></i>{{ session('error') }}
            </div>
        @endif

        <form action="{{ route('checkout.cart.store') }}" method="POST">
            @csrf

            <div class="row g-5">
                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-body p-4 p-lg-5">
                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2 mb-3">
                                Data Customer
                            </span>

                            <h4 class="fw-bold text-dark mb-2">
                                Informasi Pemesan
                            </h4>

                            <p class="text-muted mb-4">
                                Masukkan data customer yang akan digunakan untuk konfirmasi pesanan.
                            </p>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        Nama Lengkap <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           name="fullname"
                                           value="{{ old('fullname') }}"
                                           class="form-control rounded-3"
                                           placeholder="Masukkan nama lengkap"
                                           required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        No. WhatsApp <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           name="phone"
                                           value="{{ old('phone') }}"
                                           class="form-control rounded-3"
                                           placeholder="Contoh: 08123456789"
                                           required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        Email
                                    </label>
                                    <input type="email"
                                           name="email"
                                           value="{{ old('email') }}"
                                           class="form-control rounded-3"
                                           placeholder="nama@email.com">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        Gender
                                    </label>
                                    <select name="gender" class="form-select rounded-3">
                                        <option value="">Pilih Gender</option>
                                        <option value="Laki-laki" {{ old('gender') == 'Laki-laki' ? 'selected' : '' }}>
                                            Laki-laki
                                        </option>
                                        <option value="Perempuan" {{ old('gender') == 'Perempuan' ? 'selected' : '' }}>
                                            Perempuan
                                        </option>
                                        <option value="Unisex" {{ old('gender') == 'Unisex' ? 'selected' : '' }}>
                                            Unisex
                                        </option>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold">
                                        Alamat
                                    </label>
                                    <textarea name="address"
                                              class="form-control rounded-3"
                                              rows="3"
                                              placeholder="Masukkan alamat lengkap">{{ old('address') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4 p-lg-5">
                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2 mb-3">
                                Data Acara & Booking
                            </span>

                            <h4 class="fw-bold text-dark mb-2">
                                Jadwal Pemakaian
                            </h4>

                            <p class="text-muted mb-4">
                                Lengkapi detail acara agar admin dapat memvalidasi jadwal sewa dan rias.
                            </p>

                            @if($rentalDates)
                                <div class="alert alert-success rounded-4 mb-4">
                                    <i class="fa fa-calendar-check me-2"></i>
                                    Tanggal sewa sudah otomatis diambil dari keranjang:
                                    <strong>
                                        {{ \Carbon\Carbon::parse($rentalDates['rental_start'])->format('d-m-Y') }}
                                        sampai
                                        {{ \Carbon\Carbon::parse($rentalDates['rental_end'])->format('d-m-Y') }}
                                    </strong>.
                                    Sistem tetap akan mengecek ulang stok saat pesanan dibuat.
                                </div>
                            @endif

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        Jenis Acara
                                    </label>
                                    <input type="text"
                                           name="jenis_acara"
                                           value="{{ old('jenis_acara') }}"
                                           class="form-control rounded-3"
                                           placeholder="Pernikahan, Lamaran, Wisuda">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        Kategori Adat
                                    </label>
                                    <input type="text"
                                           name="kategori_adat"
                                           value="{{ old('kategori_adat') }}"
                                           class="form-control rounded-3"
                                           placeholder="Jawa, Sunda, Bali">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        Budget
                                    </label>
                                    <select name="budget" class="form-select rounded-3">
                                        <option value="">Pilih Budget</option>
                                        <option value="Rendah" {{ old('budget') == 'Rendah' ? 'selected' : '' }}>
                                            Rendah
                                        </option>
                                        <option value="Sedang" {{ old('budget') == 'Sedang' ? 'selected' : '' }}>
                                            Sedang
                                        </option>
                                        <option value="Tinggi" {{ old('budget') == 'Tinggi' ? 'selected' : '' }}>
                                            Tinggi
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        Butuh Rias? <span class="text-danger">*</span>
                                    </label>
                                    <select name="butuh_rias" class="form-select rounded-3" required>
                                        <option value="1" {{ old('butuh_rias', '1') == '1' ? 'selected' : '' }}>
                                            Ya
                                        </option>
                                        <option value="0" {{ old('butuh_rias') == '0' ? 'selected' : '' }}>
                                            Tidak
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">
                                        Tanggal Acara <span class="text-danger">*</span>
                                    </label>
                                    <input type="date"
                                           name="event_date"
                                           value="{{ $eventDateValue }}"
                                           class="form-control rounded-3"
                                           required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">
                                        Mulai Sewa <span class="text-danger">*</span>
                                    </label>
                                    <input type="date"
                                           name="rental_start"
                                           value="{{ $rentalStartValue }}"
                                           class="form-control rounded-3"
                                           required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">
                                        Selesai Sewa <span class="text-danger">*</span>
                                    </label>
                                    <input type="date"
                                           name="rental_end"
                                           value="{{ $rentalEndValue }}"
                                           class="form-control rounded-3"
                                           required>
                                           <small class="text-muted d-block mt-2">
                                                Sistem akan mengecek ulang stok berdasarkan tanggal mulai dan selesai sewa saat pesanan dibuat.
                                            </small>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        Tanggal Rias
                                    </label>
                                    <input type="date"
                                           name="makeup_date"
                                           value="{{ old('makeup_date') }}"
                                           class="form-control rounded-3">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        Metode Pembayaran <span class="text-danger">*</span>
                                    </label>
                                    <select name="payment_method" class="form-select rounded-3" required>
                                        <option value="tunai" {{ old('payment_method', 'tunai') == 'tunai' ? 'selected' : '' }}>
                                            Tunai / Cash
                                        </option>
                                        <option value="qris" {{ old('payment_method') == 'qris' ? 'selected' : '' }}>
                                            QRIS Midtrans
                                        </option>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold">
                                        Catatan
                                    </label>
                                    <textarea name="notes"
                                              class="form-control rounded-3"
                                              rows="3"
                                              placeholder="Tambahkan catatan khusus jika ada">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="bg-white rounded-4 shadow-sm p-4 position-sticky" style="top: 100px; border: 1px solid #f1e3d3;">
                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2 mb-3">
                            Ringkasan Keranjang
                        </span>

                        <h4 class="fw-bold text-dark mb-3">
                            Detail Pesanan
                        </h4>

                        <p class="text-muted mb-4">
                            Periksa kembali item, varian, dan total sebelum membuat pesanan.
                        </p>

                        <div class="border rounded-4 bg-light p-3 mb-4">
                            @foreach($cartItems as $cartItem)
                                <div class="d-flex justify-content-between gap-3 mb-3 pb-3 border-bottom">
                                    <div>
                                        <div class="fw-semibold text-dark">
                                            {{ $cartItem['item']->name }}
                                        </div>

                                        <small class="text-muted">
                                            Qty: {{ $cartItem['quantity'] }}

                                            @if($cartItem['variant'])
                                                • {{ $cartItem['variant']->size ?? '-' }}

                                                @if($cartItem['variant']->color)
                                                    / {{ $cartItem['variant']->color }}
                                                @endif
                                            @else
                                                • Tanpa varian
                                            @endif
                                        </small>
                                    </div>

                                    <div class="fw-semibold text-dark text-end">
                                        Rp{{ number_format($cartItem['total_price'], 0, ',', '.') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="border border-warning rounded-4 bg-light p-3 mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Subtotal</span>
                                <strong class="text-dark">
                                    Rp{{ number_format($subtotal, 0, ',', '.') }}
                                </strong>
                            </div>

                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Pajak</span>
                                <strong class="text-dark">
                                    Rp0
                                </strong>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between align-items-center">
                                <strong class="text-dark">Total</strong>
                                <strong class="fs-4 text-dark">
                                    Rp{{ number_format($subtotal, 0, ',', '.') }}
                                </strong>
                            </div>
                        </div>

                        <div class="alert alert-warning rounded-4 mb-4">
                            <small>
                                Setelah pesanan dibuat, sistem akan mengecek ketersediaan stok sesuai tanggal sewa,
                                menyimpan persetujuan aturan sewa, lalu membuat data order, booking, dan pembayaran.
                            </small>
                        </div>

                        <div class="card border-0 bg-light rounded-4 mb-4">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-start gap-2 mb-3">
                                    <div class="text-warning">
                                        <i class="fa fa-file-signature"></i>
                                    </div>

                                    <div>
                                        <strong class="text-dark d-block">
                                            Aturan Sewa Digital
                                        </strong>

                                        <small class="text-muted">
                                            Baca dan setujui aturan berikut sebelum membuat pesanan.
                                        </small>
                                    </div>
                                </div>

                                <div class="accordion accordion-flush" id="rentalTermsAccordionCart">
                                    @foreach($rentalTerms as $index => $term)
                                        <div class="accordion-item bg-transparent">
                                            <h2 class="accordion-header" id="rentalTermCartHeading{{ $index }}">
                                                <button class="accordion-button collapsed bg-transparent px-0 py-2 shadow-none"
                                                        type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#rentalTermCartCollapse{{ $index }}"
                                                        aria-expanded="false"
                                                        aria-controls="rentalTermCartCollapse{{ $index }}">
                                                    <span class="fw-semibold small">
                                                        {{ $index + 1 }}. {{ $term['title'] }}
                                                    </span>
                                                </button>
                                            </h2>

                                            <div id="rentalTermCartCollapse{{ $index }}"
                                                class="accordion-collapse collapse"
                                                aria-labelledby="rentalTermCartHeading{{ $index }}"
                                                data-bs-parent="#rentalTermsAccordionCart">
                                                <div class="accordion-body px-0 pt-0 pb-2">
                                                    <small class="text-muted">
                                                        {{ $term['description'] }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="border rounded-4 bg-white p-3 mt-3">
                                    <div class="form-check">
                                        <input class="form-check-input"
                                            type="checkbox"
                                            name="agree_terms"
                                            value="1"
                                            id="agreeTermsCart"
                                            {{ old('agree_terms') ? 'checked' : '' }}
                                            required>

                                        <label class="form-check-label small" for="agreeTermsCart">
                                            Saya menyetujui aturan sewa, pengembalian barang, denda keterlambatan,
                                            dan tanggung jawab kerusakan/hilang.
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-dark rounded-pill w-100 py-3">
                            <i class="fa fa-check-circle me-2"></i>Buat Pesanan
                        </button>

                        <a href="{{ route('cart.index') }}" class="btn btn-outline-dark rounded-pill w-100 py-3 mt-2">
                            <i class="fa fa-arrow-left me-2"></i>Kembali ke Keranjang
                        </a>

                        @if($contact)
                            <div class="alert alert-warning rounded-4 mt-4 mb-0 text-center">
                                <small>
                                    Butuh bantuan?
                                    <a href="{{ $contact->whatsapp_url }}"
                                       target="_blank"
                                       class="fw-semibold text-dark">
                                        Hubungi {{ $contact->contact_name }}
                                    </a>
                                </small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection
