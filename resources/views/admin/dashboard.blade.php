@extends('admin.layouts.master')
@section('title', 'Dashboard')

@section('css')
@endsection

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h3>Selamat Datang, {{ Auth::user()->fullname }}!</h3>
            <p class="text-muted mb-0">
                Ringkasan aktivitas sistem persewaan baju adat dan jasa rias Quin Salon.
            </p>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="row">
        <div class="col-12">
            <div class="row">

                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row align-items-center">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                    <div class="stats-icon purple mb-2">
                                        <i class="iconly-boldWallet"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Pesanan Hari Ini</h6>
                                    <h6 class="font-extrabold mb-0">{{ $todayOrders }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row align-items-center">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                    <div class="stats-icon blue mb-2">
                                        <i class="iconly-boldBuy"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Pendapatan Hari Ini</h6>
                                    <h6 class="font-extrabold mb-0">
                                        Rp{{ number_format($todayRevenue, 0, ',', '.') }}
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row align-items-center">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                    <div class="stats-icon green mb-2">
                                        <i class="iconly-boldFolder"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Total Pesanan</h6>
                                    <h6 class="font-extrabold mb-0">{{ $totalOrders }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row align-items-center">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                    <div class="stats-icon red mb-2">
                                        <i class="iconly-boldProfile"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Total Pendapatan</h6>
                                    <h6 class="font-extrabold mb-0">
                                        Rp{{ number_format($totalRevenue, 0, ',', '.') }}
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row align-items-center">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                    <div class="stats-icon purple mb-2">
                                        <i class="iconly-boldCategory"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Item Aktif</h6>
                                    <h6 class="font-extrabold mb-0">{{ $activeItems }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row align-items-center">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                    <div class="stats-icon blue mb-2">
                                        <i class="iconly-boldBag-2"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Bundle Aktif</h6>
                                    <h6 class="font-extrabold mb-0">{{ $activeBundles }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-4 col-md-12">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row align-items-center">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                    <div class="stats-icon green mb-2">
                                        <i class="iconly-boldTime-Circle"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Order Pending</h6>
                                    <h6 class="font-extrabold mb-0">{{ $pendingOrders }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-12 col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>Ringkasan Sistem</h4>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-3">
                                Dashboard ini menampilkan gambaran umum aktivitas sistem Quin Salon, mulai dari
                                pengelolaan item, bundle rekomendasi, transaksi order, hingga pembayaran.
                            </p>

                            <div class="table-responsive">
                                <table class="table table-striped mb-0">
                                    <tbody>
                                        <tr>
                                            <th>Pesanan Hari Ini</th>
                                            <td>{{ $todayOrders }}</td>
                                        </tr>
                                        <tr>
                                            <th>Total Pesanan</th>
                                            <td>{{ $totalOrders }}</td>
                                        </tr>
                                        <tr>
                                            <th>Item Aktif</th>
                                            <td>{{ $activeItems }}</td>
                                        </tr>
                                        <tr>
                                            <th>Bundle Aktif</th>
                                            <td>{{ $activeBundles }}</td>
                                        </tr>
                                        <tr>
                                            <th>Order Pending</th>
                                            <td>{{ $pendingOrders }}</td>
                                        </tr>
                                        <tr>
                                            <th>Pendapatan Hari Ini</th>
                                            <td>Rp{{ number_format($todayRevenue, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Total Pendapatan</th>
                                            <td>Rp{{ number_format($totalRevenue, 0, ',', '.') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Aksi Cepat</h4>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('orders.index') }}" class="btn btn-outline-primary">
                                    <i class="bi bi-receipt-cutoff me-1"></i>Data Order
                                </a>

                                <a href="{{ route('payments.index') }}" class="btn btn-outline-primary">
                                    <i class="bi bi-wallet2 me-1"></i>Data Pembayaran
                                </a>

                                <a href="{{ route('rental-bookings.index') }}" class="btn btn-outline-primary">
                                    <i class="bi bi-calendar-check me-1"></i>Data Booking
                                </a>

                                <a href="{{ route('items.index') }}" class="btn btn-outline-primary">
                                    <i class="bi bi-bag-fill me-1"></i>Data Item
                                </a>

                                <a href="{{ route('item-variants.index') }}" class="btn btn-outline-primary">
                                    <i class="bi bi-grid-3x3-gap-fill me-1"></i>Item Variant
                                </a>

                                <a href="{{ route('bundles.index') }}" class="btn btn-outline-primary">
                                    <i class="bi bi-box2-heart-fill me-1"></i>Data Bundle
                                </a>

                                <a href="{{ route('recommendation-rules.index') }}" class="btn btn-outline-primary">
                                    <i class="bi bi-diagram-3-fill me-1"></i>Rule Rekomendasi
                                </a>

                                <a href="{{ route('contact-settings.index') }}" class="btn btn-outline-primary">
                                    <i class="bi bi-whatsapp me-1"></i>Contact Setting
                                </a>

                                @if (Auth::user()->role && Auth::user()->role->role_name === 'admin')
                                    <a href="{{ route('categories.index') }}" class="btn btn-outline-primary">
                                        <i class="bi bi-tags-fill me-1"></i>Data Kategori
                                    </a>

                                    <a href="{{ route('users.index') }}" class="btn btn-outline-primary">
                                        <i class="bi bi-people-fill me-1"></i>Data User
                                    </a>

                                    <a href="{{ route('roles.index') }}" class="btn btn-outline-primary">
                                        <i class="bi bi-person-fill-gear me-1"></i>Data Role
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>
@endsection

@section('script')
@endsection
