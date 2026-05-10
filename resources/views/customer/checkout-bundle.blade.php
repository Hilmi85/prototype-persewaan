@extends('customer.layouts.master')

@section('title', 'Checkout Paket - Quin Salon')

@section('content')
@php
    $bundlePrice = $bundle->price ?? $bundle->bundle_price ?? 0;

    $rentalTermsService = app(\App\Services\RentalTermsService::class);
    $rentalTerms = $rentalTermsService->rules();
@endphp

<section class="container-fluid page-header customer-hero py-5 mb-5">
    <div class="container py-5">
        <div class="row justify-content-center text-center">
            <div class="col-lg-9">
                <span class="badge bg-warning text-dark rounded-pill px-4 py-2 mb-3">
                    Quin Salon • Checkout Paket Bundling
                </span>

                <h1 class="display-4 text-white fw-bold mb-3">
                    {{ $bundle->bundle_name }}
                </h1>

                <p class="text-white mx-auto mb-4 max-w-760">
                    Pilih varian item yang tersedia, lalu lengkapi data customer dan jadwal booking.
                </p>

                <div class="d-flex justify-content-center flex-wrap gap-2">
                    <a href="#checkout-bundle-form" class="btn btn-dark rounded-pill px-4 py-3">
                        <i class="fa fa-arrow-down me-2"></i>Isi Checkout
                    </a>

                    <a href="{{ route('bundle.show', $bundle->id) }}" class="btn btn-outline-light rounded-pill px-4 py-3">
                        <i class="fa fa-arrow-left me-2"></i>Detail Paket
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="checkout-bundle-form" class="container-fluid py-5 bg-cream">
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

        <form action="{{ route('checkout.bundle.store', $bundle->id) }}" method="POST">
            @csrf

            <div class="row g-4 align-items-start">
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
                                Masukkan data customer yang akan digunakan untuk konfirmasi pesanan paket.
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
                                        Nomor WhatsApp <span class="text-danger">*</span>
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
                                        <option value="Laki-laki" {{ old('gender', $bundle->gender) == 'Laki-laki' ? 'selected' : '' }}>
                                            Laki-laki
                                        </option>
                                        <option value="Perempuan" {{ old('gender', $bundle->gender) == 'Perempuan' ? 'selected' : '' }}>
                                            Perempuan
                                        </option>
                                        <option value="Unisex" {{ old('gender', $bundle->gender) == 'Unisex' ? 'selected' : '' }}>
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

                    <div class="card border-0 shadow-sm rounded-4 mb-4">
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

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        Jenis Acara
                                    </label>
                                    <input type="text"
                                           name="jenis_acara"
                                           value="{{ old('jenis_acara', $bundle->jenis_acara) }}"
                                           class="form-control rounded-3"
                                           placeholder="Pernikahan, Lamaran, Wisuda">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        Kategori Adat
                                    </label>
                                    <input type="text"
                                           name="kategori_adat"
                                           value="{{ old('kategori_adat', $bundle->kategori_adat) }}"
                                           class="form-control rounded-3"
                                           placeholder="Jawa, Sunda, Bali">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        Budget
                                    </label>
                                    <select name="budget" class="form-select rounded-3">
                                        <option value="">Pilih Budget</option>
                                        <option value="Rendah" {{ old('budget', $bundle->budget_category) == 'Rendah' ? 'selected' : '' }}>
                                            Rendah
                                        </option>
                                        <option value="Sedang" {{ old('budget', $bundle->budget_category) == 'Sedang' ? 'selected' : '' }}>
                                            Sedang
                                        </option>
                                        <option value="Tinggi" {{ old('budget', $bundle->budget_category) == 'Tinggi' ? 'selected' : '' }}>
                                            Tinggi
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        Butuh Rias? <span class="text-danger">*</span>
                                    </label>
                                    <select name="butuh_rias" class="form-select rounded-3" required>
                                        <option value="1" {{ old('butuh_rias', $bundle->butuh_rias ? '1' : '0') == '1' ? 'selected' : '' }}>
                                            Ya
                                        </option>
                                        <option value="0" {{ old('butuh_rias', $bundle->butuh_rias ? '1' : '0') == '0' ? 'selected' : '' }}>
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
                                           value="{{ old('event_date') }}"
                                           class="form-control rounded-3"
                                           required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">
                                        Mulai Sewa <span class="text-danger">*</span>
                                    </label>
                                    <input type="date"
                                           name="rental_start"
                                           value="{{ old('rental_start') }}"
                                           class="form-control rounded-3"
                                           required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">
                                        Selesai Sewa <span class="text-danger">*</span>
                                    </label>
                                    <input type="date"
                                           name="rental_end"
                                           value="{{ old('rental_end') }}"
                                           class="form-control rounded-3"
                                           required>
                                           <small class="text-muted d-block mt-2">
                                                Sistem akan mengecek ulang stok setiap item paket berdasarkan tanggal sewa saat pesanan dibuat.
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

                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4 p-lg-5">
                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2 mb-3">
                                Pilih Varian Item Paket
                            </span>

                            <h4 class="fw-bold text-dark mb-2">
                                Varian Item Paket
                            </h4>

                            <p class="text-muted mb-4">
                                Pilih varian untuk item yang membutuhkan ukuran atau warna.
                                Layanan rias tidak perlu memilih varian.
                            </p>

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Item</th>
                                            <th>Qty</th>
                                            <th>Varian</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($bundle->bundleItems as $bundleItem)
                                            @php
                                                $item = $bundleItem->item;
                                                $availableVariants = $item
                                                    ? $item->itemVariants->where('is_active', true)->where('available_stock', '>', 0)
                                                    : collect();
                                            @endphp

                                            <tr>
                                                <td>
                                                    <div class="fw-semibold text-dark">
                                                        {{ $item->name ?? '-' }}
                                                    </div>

                                                    <small class="text-muted">
                                                        {{ $item->category->cat_name ?? '-' }}

                                                        @if($item?->item_type)
                                                            • {{ str_replace('_', ' ', $item->item_type) }}
                                                        @endif
                                                    </small>
                                                </td>

                                                <td>
                                                    {{ $bundleItem->quantity }}
                                                </td>

                                                <td>
                                                    @if($item && $item->item_type === 'jasa_rias')
                                                        <span class="badge bg-primary rounded-pill">
                                                            Layanan rias, tanpa varian
                                                        </span>
                                                    @elseif($availableVariants->count())
                                                        <select name="bundle_variants[{{ $item->id }}]" class="form-select rounded-3" required>
                                                            <option value="">Pilih Varian</option>

                                                            @foreach($availableVariants as $variant)
                                                                <option value="{{ $variant->id }}"
                                                                        {{ old("bundle_variants.{$item->id}") == $variant->id ? 'selected' : '' }}>
                                                                    {{ $variant->size ?? '-' }}
                                                                    @if($variant->color)
                                                                        / {{ $variant->color }}
                                                                    @endif
                                                                    • Stok {{ $variant->available_stock }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    @else
                                                        <span class="badge bg-warning text-dark rounded-pill">
                                                            Varian belum tersedia
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="alert alert-warning rounded-4 mt-4 mb-0">
                                <strong>Catatan:</strong>
                                <div class="small mt-1">
                                    Jika varian belum tersedia, admin perlu melakukan konfirmasi stok sebelum pesanan diproses.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm rounded-4 sticky-summary">
                        <div class="card-body p-4">
                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2 mb-3">
                                {{ $bundle->is_custom ? 'Paket Custom' : 'Paket Rekomendasi' }}
                            </span>

                            <h4 class="fw-bold text-dark mb-2">
                                {{ $bundle->bundle_name }}
                            </h4>

                            <p class="text-muted">
                                {{ $bundle->description ?: 'Paket bundling sesuai kebutuhan customer.' }}
                            </p>

                            <div class="border rounded-4 bg-light p-3 mb-4">
                                @foreach($bundle->bundleItems as $bundleItem)
                                    <div class="d-flex justify-content-between gap-3 mb-3 pb-3 border-bottom">
                                        <div>
                                            <div class="fw-semibold text-dark">
                                                {{ $bundleItem->item->name ?? '-' }}
                                            </div>

                                            <small class="text-muted">
                                                Qty: {{ $bundleItem->quantity }}
                                            </small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="border border-warning rounded-4 bg-light p-3 mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Harga Paket</span>
                                    <strong class="text-dark">
                                        Rp{{ number_format($bundlePrice, 0, ',', '.') }}
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
                                        Rp{{ number_format($bundlePrice, 0, ',', '.') }}
                                    </strong>
                                </div>
                            </div>

                            <div class="alert alert-warning rounded-4 mb-4">
                                <small>
                                    Setelah pesanan dibuat, sistem akan menyimpan persetujuan aturan sewa,
                                    lalu membuat order, booking, dan pembayaran paket.
                                </small>
                            </div>

                            <div class="d-grid gap-2">

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
                                                    Baca dan setujui aturan berikut sebelum membuat pesanan paket.
                                                </small>
                                            </div>
                                        </div>

                                        <div class="accordion accordion-flush" id="rentalTermsAccordionBundle">
                                            @foreach($rentalTerms as $index => $term)
                                                <div class="accordion-item bg-transparent">
                                                    <h2 class="accordion-header" id="rentalTermBundleHeading{{ $index }}">
                                                        <button class="accordion-button collapsed bg-transparent px-0 py-2 shadow-none"
                                                                type="button"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#rentalTermBundleCollapse{{ $index }}"
                                                                aria-expanded="false"
                                                                aria-controls="rentalTermBundleCollapse{{ $index }}">
                                                            <span class="fw-semibold small">
                                                                {{ $index + 1 }}. {{ $term['title'] }}
                                                            </span>
                                                        </button>
                                                    </h2>

                                                    <div id="rentalTermBundleCollapse{{ $index }}"
                                                        class="accordion-collapse collapse"
                                                        aria-labelledby="rentalTermBundleHeading{{ $index }}"
                                                        data-bs-parent="#rentalTermsAccordionBundle">
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
                                                    id="agreeTermsBundle"
                                                    {{ old('agree_terms') ? 'checked' : '' }}
                                                    required>

                                                <label class="form-check-label small" for="agreeTermsBundle">
                                                    Saya menyetujui aturan sewa, pengembalian barang, denda keterlambatan,
                                                    dan tanggung jawab kerusakan/hilang.
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-dark rounded-pill py-3">
                                    <i class="fa fa-check-circle me-2"></i>Buat Pesanan Paket
                                </button>

                                <a href="{{ route('bundle.show', $bundle->id) }}" class="btn btn-outline-dark rounded-pill py-3">
                                    <i class="fa fa-arrow-left me-2"></i>Kembali ke Detail Paket
                                </a>
                            </div>

                            @isset($contact)
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
                            @endisset
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection
