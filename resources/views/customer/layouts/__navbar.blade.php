<div class="container-fluid fixed-top px-0 brand-navbar">
    <div class="container px-3 px-md-0">
        <nav class="navbar navbar-expand-xl py-2 py-md-3">
            <a href="{{ route('home') }}" class="navbar-brand brand-logo d-flex flex-column justify-content-center text-decoration-none me-2">
                <h2 class="brand-title mb-0 fw-bold">Quin Salon</h2>
                <small class="brand-subtitle">Baju Adat & Jasa Rias</small>
            </a>

            @php
                $cartCount = collect(session('cart', []))->sum('quantity');

                $isCatalogMenuActive =
                    request()->routeIs('catalog') ||
                    request()->routeIs('catalog.show') ||
                    request()->routeIs('accessories.index') ||
                    request()->routeIs('rias.index');
            @endphp

            <div class="d-flex align-items-center gap-2 d-xl-none ms-auto">
                <a href="{{ route('cart.index') }}" class="btn btn-icon-soft position-relative d-flex align-items-center justify-content-center rounded-circle">
                    <i class="fa fa-cart-shopping"></i>

                    @if($cartCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger badge-notification">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>

                <button class="navbar-toggler btn-icon-soft border-0 shadow-none d-flex align-items-center justify-content-center rounded-circle"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#navbarCollapse"
                        aria-controls="navbarCollapse"
                        aria-expanded="false"
                        aria-label="Toggle navigation">
                    <span class="fa fa-bars"></span>
                </button>
            </div>

            <div class="collapse navbar-collapse mt-3 mt-xl-0" id="navbarCollapse">
                <div class="d-xl-none p-3 p-md-4 rounded-4 mobile-menu-card">
                    <div class="navbar-nav text-start mobile-nav-stack">
                        <a href="{{ route('home') }}"
                           class="nav-item nav-link nav-brand-link fw-semibold px-3 py-2 rounded-pill {{ request()->routeIs('home') ? 'active' : '' }}">
                            Beranda
                        </a>

                        <div class="nav-item dropdown">
                            <a href="#"
                            class="nav-link nav-brand-link fw-semibold px-3 py-2 rounded-pill dropdown-toggle {{ $isCatalogMenuActive ? 'active' : '' }}"
                            id="mobileCatalogDropdown"
                            role="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">
                                Katalog
                            </a>

                            <ul class="dropdown-menu bg-white border-0 shadow-sm rounded-3 mt-2 p-2" aria-labelledby="mobileCatalogDropdown">
                                <li>
                                    <a href="{{ route('catalog') }}"
                                    class="dropdown-item text-dark rounded-3 py-2 {{ request()->routeIs('catalog') || request()->routeIs('catalog.show') ? 'bg-light fw-bold border-start border-4 border-warning' : '' }}">
                                        <i class="fa fa-shirt me-2 text-dark"></i>
                                        Katalog Baju Adat
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('accessories.index') }}"
                                    class="dropdown-item text-dark rounded-3 py-2 {{ request()->routeIs('accessories.index') ? 'bg-light fw-bold border-start border-4 border-warning' : '' }}">
                                        <i class="fa fa-crown me-2 text-dark"></i>
                                        Aksesoris
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('rias.index') }}"
                                    class="dropdown-item text-dark rounded-3 py-2 {{ request()->routeIs('rias.index') ? 'bg-light fw-bold border-start border-4 border-warning' : '' }}">
                                        <i class="fa fa-wand-magic-sparkles me-2 text-dark"></i>
                                        Jasa Rias
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <a href="{{ route('recommendation.index') }}"
                           class="nav-item nav-link nav-brand-link fw-semibold px-3 py-2 rounded-pill {{ request()->routeIs('recommendation.index') || request()->routeIs('recommendation.process') ? 'active' : '' }}">
                            Rekomendasi Paket
                        </a>

                        <a  href="{{ route('order.track.index') }}"
                            class="nav-item nav-link nav-brand-link fw-semibold px-3 py-2 rounded-pill {{ request()->routeIs('order.track.index') || request()->routeIs('order.track.check') ? 'active' : '' }}">
                            Cek Pesanan
                        </a>

                        <a href="#footer"
                           class="nav-item nav-link nav-brand-link fw-semibold px-3 py-2 rounded-pill">
                            Kontak
                        </a>

                        <div class="pt-3 mt-2 mobile-menu-divider">
                            <a href="{{ route('cart.index') }}" class="btn btn-brand w-100 rounded-pill py-2">
                                <i class="fa fa-cart-shopping me-2"></i>Lihat Keranjang

                                @if($cartCount > 0)
                                    <span class="badge rounded-pill bg-light text-dark ms-2">
                                        {{ $cartCount }}
                                    </span>
                                @endif
                            </a>
                        </div>
                    </div>
                </div>

                {{-- DESKTOP NAVBAR --}}
                <div class="d-none d-xl-flex navbar-nav mx-auto align-items-center gap-2 gap-xxl-3">
                    <a href="{{ route('home') }}"
                       class="nav-item nav-link nav-brand-link fw-semibold px-3 py-2 rounded-pill {{ request()->routeIs('home') ? 'active' : '' }}">
                        Beranda
                    </a>

                    <div class="nav-item dropdown">
                        <a href="{{ route('catalog') }}"
                        class="nav-link nav-brand-link fw-semibold px-3 py-2 rounded-pill dropdown-toggle {{ $isCatalogMenuActive ? 'active' : '' }}"
                        id="desktopCatalogDropdown"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                            Katalog
                        </a>

                        <ul class="dropdown-menu bg-white border-0 shadow-sm rounded-3 mt-2 p-2" aria-labelledby="desktopCatalogDropdown">
                            <li>
                                <a href="{{ route('catalog') }}"
                                class="dropdown-item text-dark rounded-3 py-2 {{ request()->routeIs('catalog') || request()->routeIs('catalog.show') ? 'bg-light fw-bold border-start border-4 border-warning' : '' }}">
                                    <i class="fa fa-shirt me-2 text-dark"></i>
                                    Katalog Baju Adat
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('accessories.index') }}"
                                class="dropdown-item text-dark rounded-3 py-2 {{ request()->routeIs('accessories.index') ? 'bg-light fw-bold border-start border-4 border-warning' : '' }}">
                                    <i class="fa fa-crown me-2 text-dark"></i>
                                    Aksesoris
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('rias.index') }}"
                                class="dropdown-item text-dark rounded-3 py-2 {{ request()->routeIs('rias.index') ? 'bg-light fw-bold border-start border-4 border-warning' : '' }}">
                                    <i class="fa fa-wand-magic-sparkles me-2 text-dark"></i>
                                    Jasa Rias
                                </a>
                            </li>
                        </ul>
                    </div>

                    <a href="{{ route('recommendation.index') }}"
                       class="nav-item nav-link nav-brand-link fw-semibold px-3 py-2 rounded-pill {{ request()->routeIs('recommendation.index') || request()->routeIs('recommendation.process') ? 'active' : '' }}">
                        Rekomendasi Paket
                    </a>

                    <a href="{{ route('order.track.index') }}"
                        class="nav-item nav-link nav-brand-link fw-semibold px-3 py-2 rounded-pill {{ request()->routeIs('order.track.index') || request()->routeIs('order.track.check') ? 'active' : '' }}">
                        Cek Pesanan
                    </a>

                    <a href="#footer"
                       class="nav-item nav-link nav-brand-link fw-semibold px-3 py-2 rounded-pill">
                        Kontak
                    </a>
                </div>

                <div class="d-none d-xl-flex align-items-center ms-xl-3">
                    <a href="{{ route('cart.index') }}"
                       class="btn btn-cart-pill position-relative d-flex align-items-center justify-content-center rounded-pill px-4 py-2">
                        <i class="fa fa-cart-shopping"></i>

                        @if($cartCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger badge-notification">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>
                </div>
            </div>
        </nav>
    </div>
</div>
