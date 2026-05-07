@extends('customer.layouts.master')

@section('title', 'Checkout Keranjang - Quin Salon')

@section('content')
<div class="container-fluid page-header py-5 mb-5"
     style="margin-top: -55px !important; padding-top: 170px !important; background: linear-gradient(rgba(60, 42, 33, 0.68), rgba(60, 42, 33, 0.68)), url('{{ asset('img_item_upload/indo.jpg') }}'); background-position: center center; background-repeat: no-repeat; background-size: cover;">
    <div class="container py-5 text-center">
        <span class="badge rounded-pill px-4 py-2 mb-3"
              style="background-color: rgba(255,255,255,0.12); color: #f5d2a6;">
            Checkout Keranjang
        </span>

        <h1 class="display-4 text-white fw-bold">Lengkapi Data Pesanan</h1>

        <p class="text-white mb-0">
            Data ini digunakan untuk membuat order, booking, dan pembayaran.
        </p>
    </div>
</div>

<div class="container-fluid py-5" style="background-color: #fffaf5;">
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
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('checkout.cart.store') }}" method="POST">
            @csrf

            <div class="row g-5">
                <div class="col-lg-7">
                    <div class="bg-white rounded-4 shadow-sm p-4 p-md-5" style="border: 1px solid #f1e3d3;">
                        <h4 class="fw-bold mb-4" style="color: #8b5e3c;">
                            Data Customer
                        </h4>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="fullname" value="{{ old('fullname') }}" class="form-control rounded-3" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">No. WhatsApp <span class="text-danger">*</span></label>
                                <input type="text" name="phone" value="{{ old('phone') }}" class="form-control rounded-3" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control rounded-3">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Gender</label>
                                <select name="gender" class="form-select rounded-3">
                                    <option value="">Pilih Gender</option>
                                    <option value="Laki-laki" {{ old('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ old('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    <option value="Unisex" {{ old('gender') == 'Unisex' ? 'selected' : '' }}>Unisex</option>
                                </select>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label fw-semibold">Alamat</label>
                                <textarea name="address" class="form-control rounded-3" rows="3">{{ old('address') }}</textarea>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h4 class="fw-bold mb-4" style="color: #8b5e3c;">
                            Data Acara dan Booking
                        </h4>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Jenis Acara</label>
                                <input type="text" name="jenis_acara" value="{{ old('jenis_acara') }}" class="form-control rounded-3" placeholder="Pernikahan, Lamaran, Wisuda">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Kategori Adat</label>
                                <input type="text" name="kategori_adat" value="{{ old('kategori_adat') }}" class="form-control rounded-3" placeholder="Jawa, Sunda, Bali">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Budget</label>
                                <select name="budget" class="form-select rounded-3">
                                    <option value="">Pilih Budget</option>
                                    <option value="Rendah" {{ old('budget') == 'Rendah' ? 'selected' : '' }}>Rendah</option>
                                    <option value="Sedang" {{ old('budget') == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                                    <option value="Tinggi" {{ old('budget') == 'Tinggi' ? 'selected' : '' }}>Tinggi</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Butuh Rias? <span class="text-danger">*</span></label>
                                <select name="butuh_rias" class="form-select rounded-3" required>
                                    <option value="1" {{ old('butuh_rias', '1') == '1' ? 'selected' : '' }}>Ya</option>
                                    <option value="0" {{ old('butuh_rias') == '0' ? 'selected' : '' }}>Tidak</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Tanggal Acara <span class="text-danger">*</span></label>
                                <input type="date" name="event_date" value="{{ old('event_date') }}" class="form-control rounded-3" required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Mulai Sewa <span class="text-danger">*</span></label>
                                <input type="date" name="rental_start" value="{{ old('rental_start') }}" class="form-control rounded-3" required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Selesai Sewa <span class="text-danger">*</span></label>
                                <input type="date" name="rental_end" value="{{ old('rental_end') }}" class="form-control rounded-3" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Tanggal Rias</label>
                                <input type="date" name="makeup_date" value="{{ old('makeup_date') }}" class="form-control rounded-3">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Metode Pembayaran <span class="text-danger">*</span></label>
                                <select name="payment_method" class="form-select rounded-3" required>
                                    <option value="tunai" {{ old('payment_method', 'tunai') == 'tunai' ? 'selected' : '' }}>Tunai / Cash</option>
                                    <option value="qris" {{ old('payment_method') == 'qris' ? 'selected' : '' }}>QRIS Midtrans</option>
                                </select>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label fw-semibold">Catatan</label>
                                <textarea name="notes" class="form-control rounded-3" rows="3">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="bg-white rounded-4 shadow-sm p-4 position-sticky" style="top: 100px; border: 1px solid #f1e3d3;">
                        <h4 class="fw-bold mb-4" style="color: #8b5e3c;">
                            Ringkasan Keranjang
                        </h4>

                        @foreach($cartItems as $cartItem)
                            <div class="d-flex justify-content-between gap-3 mb-3 pb-3 border-bottom">
                                <div>
                                    <div class="fw-semibold">{{ $cartItem['item']->name }}</div>
                                    <small class="text-muted">
                                        Qty: {{ $cartItem['quantity'] }}
                                        @if($cartItem['variant'])
                                            • {{ $cartItem['variant']->size ?? '-' }} / {{ $cartItem['variant']->color ?? '-' }}
                                        @else
                                            • Tanpa varian
                                        @endif
                                    </small>
                                </div>

                                <div class="fw-semibold text-end" style="color: #8b5e3c;">
                                    Rp{{ number_format($cartItem['total_price'], 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach

                        <div class="p-3 rounded-4 mt-4" style="background-color: #fff7ef; border: 1px solid #f0dfcf;">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal</span>
                                <strong>Rp{{ number_format($subtotal, 0, ',', '.') }}</strong>
                            </div>

                            <div class="d-flex justify-content-between">
                                <span>Pajak</span>
                                <strong>Rp0</strong>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between align-items-center">
                                <strong>Total</strong>
                                <strong class="fs-4" style="color: #8b5e3c;">
                                    Rp{{ number_format($subtotal, 0, ',', '.') }}
                                </strong>
                            </div>
                        </div>

                        <button type="submit"
                                class="btn rounded-pill w-100 py-3 mt-4"
                                style="background-color: #8b5e3c; color: #fff;">
                            <i class="fa fa-check-circle me-2"></i>Buat Pesanan
                        </button>

                        <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary rounded-pill w-100 py-3 mt-2">
                            Kembali ke Keranjang
                        </a>

                        @if($contact)
                            <div class="mt-4 small text-muted text-center">
                                Butuh bantuan?
                                <a href="{{ $contact->whatsapp_url }}" target="_blank" style="color: #8b5e3c;">
                                    Hubungi {{ $contact->contact_name }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
