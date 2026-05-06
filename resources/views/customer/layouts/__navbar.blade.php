<div class="container-fluid fixed-top px-0"
     style="z-index: 1030; background: linear-gradient(180deg, rgba(255,250,245,0.97) 0%, rgba(255,248,242,0.94) 100%); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(216,184,146,0.22); box-shadow: 0 8px 24px rgba(111,78,55,0.08);">
    <div class="container px-3 px-md-0">
        <nav class="navbar navbar-expand-xl py-2 py-md-3" style="min-height: 82px;">
            <a href="{{ route('home') }}"
               class="navbar-brand d-flex flex-column justify-content-center text-decoration-none me-2"
               style="max-width: 72%;">
                <h2 class="mb-0 fw-bold"
                    style="color: #8b5e3c; font-size: clamp(1.35rem, 2vw, 1.95rem); line-height: 1.05; letter-spacing: 0.3px;">
                    Quin Salon
                </h2>
                <small style="color: #c8a97e; letter-spacing: 1px; font-size: clamp(0.68rem, 1.5vw, 0.82rem);">
                    Baju Adat & Jasa Rias
                </small>
            </a>

            @php
                $cartCount = collect(session('cart', []))->sum('quantity');
            @endphp

            <div class="d-flex align-items-center gap-2 d-xl-none ms-auto">
                <a href="{{ route('cart.index') }}"
                   class="btn position-relative d-flex align-items-center justify-content-center rounded-circle"
                   style="width: 42px; height: 42px; background: #fff; color: #8b5e3c; border: 1px solid rgba(216,184,146,0.45); box-shadow: 0 4px 12px rgba(139,94,60,0.08);">
                    <i class="fa fa-cart-shopping"></i>
                    @if($cartCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                              style="font-size: 0.65rem; min-width: 18px; height: 18px; line-height: 18px; padding: 0 5px;">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>

                <button class="navbar-toggler border-0 shadow-none d-flex align-items-center justify-content-center rounded-circle"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#navbarCollapse"
                        aria-controls="navbarCollapse"
                        aria-expanded="false"
                        aria-label="Toggle navigation"
                        style="width: 42px; height: 42px; background: #fff; box-shadow: 0 4px 12px rgba(139,94,60,0.08);">
                    <span class="fa fa-bars" style="color: #8b5e3c; font-size: 1rem;"></span>
                </button>
            </div>

            <div class="collapse navbar-collapse mt-3 mt-xl-0" id="navbarCollapse">
                <div class="d-xl-none p-3 p-md-4 rounded-4"
                     style="background: linear-gradient(180deg, #fffdfb 0%, #fff7ef 100%); border: 1px solid rgba(216,184,146,0.28); box-shadow: 0 14px 30px rgba(111,78,55,0.10);">
                    <div class="navbar-nav text-start" style="row-gap: 0.55rem;">
                        <a href="{{ route('home') }}"
                           class="nav-item nav-link fw-semibold px-3 py-2 rounded-pill {{ request()->routeIs('home') ? 'active' : '' }}"
                           style="color: {{ request()->routeIs('home') ? '#8b5e3c' : '#6f4e37' }}; background: {{ request()->routeIs('home') ? 'linear-gradient(90deg, rgba(216,184,146,0.18), rgba(216,184,146,0.07))' : 'transparent' }};">
                            Beranda
                        </a>

                        <a href="{{ route('catalog') }}"
                           class="nav-item nav-link fw-semibold px-3 py-2 rounded-pill {{ request()->routeIs('catalog') || request()->routeIs('catalog.show') ? 'active' : '' }}"
                           style="color: {{ request()->routeIs('catalog') || request()->routeIs('catalog.show') ? '#8b5e3c' : '#6f4e37' }}; background: {{ request()->routeIs('catalog') || request()->routeIs('catalog.show') ? 'linear-gradient(90deg, rgba(216,184,146,0.18), rgba(216,184,146,0.07))' : 'transparent' }};">
                            Katalog
                        </a>

                        <a href="{{ route('rias.index') }}"
                           class="nav-item nav-link fw-semibold px-3 py-2 rounded-pill {{ request()->routeIs('rias.index') ? 'active' : '' }}"
                           style="color: {{ request()->routeIs('rias.index') ? '#8b5e3c' : '#6f4e37' }}; background: {{ request()->routeIs('rias.index') ? 'linear-gradient(90deg, rgba(216,184,146,0.18), rgba(216,184,146,0.07))' : 'transparent' }};">
                            Jasa Rias
                        </a>

                        <a href="{{ route('recommendation.index') }}"
                           class="nav-item nav-link fw-semibold px-3 py-2 rounded-pill {{ request()->routeIs('recommendation.index') || request()->routeIs('recommendation.process') ? 'active' : '' }}"
                           style="color: {{ request()->routeIs('recommendation.index') || request()->routeIs('recommendation.process') ? '#8b5e3c' : '#6f4e37' }}; background: {{ request()->routeIs('recommendation.index') || request()->routeIs('recommendation.process') ? 'linear-gradient(90deg, rgba(216,184,146,0.18), rgba(216,184,146,0.07))' : 'transparent' }};">
                            Rekomendasi Paket
                        </a>

                        <a href="#footer"
                           class="nav-item nav-link fw-semibold px-3 py-2 rounded-pill"
                           style="color: #6f4e37;">
                            Kontak
                        </a>

                        <div class="pt-3 mt-2" style="border-top: 1px dashed rgba(216,184,146,0.45);">
                            <a href="{{ route('cart.index') }}"
                               class="btn w-100 rounded-pill py-2"
                               style="background: linear-gradient(90deg, #8b5e3c, #a47148); color: #fff; border: none; box-shadow: 0 8px 18px rgba(139,94,60,0.18);">
                                <i class="fa fa-cart-shopping me-2"></i>Lihat Keranjang
                                @if($cartCount > 0)
                                    <span class="badge rounded-pill bg-light text-dark ms-2">{{ $cartCount }}</span>
                                @endif
                            </a>
                        </div>
                    </div>
                </div>

                <div class="d-none d-xl-flex navbar-nav mx-auto align-items-center gap-2 gap-xxl-3">
                    <a href="{{ route('home') }}"
                       class="nav-item nav-link fw-semibold px-3 py-2 rounded-pill {{ request()->routeIs('home') ? 'active' : '' }}"
                       style="color: {{ request()->routeIs('home') ? '#8b5e3c' : '#6f4e37' }}; background: {{ request()->routeIs('home') ? 'linear-gradient(90deg, rgba(216,184,146,0.16), rgba(216,184,146,0.06))' : 'transparent' }}; transition: all 0.25s ease;">
                        Beranda
                    </a>

                    <a href="{{ route('catalog') }}"
                       class="nav-item nav-link fw-semibold px-3 py-2 rounded-pill {{ request()->routeIs('catalog') || request()->routeIs('catalog.show') ? 'active' : '' }}"
                       style="color: {{ request()->routeIs('catalog') || request()->routeIs('catalog.show') ? '#8b5e3c' : '#6f4e37' }}; background: {{ request()->routeIs('catalog') || request()->routeIs('catalog.show') ? 'linear-gradient(90deg, rgba(216,184,146,0.16), rgba(216,184,146,0.06))' : 'transparent' }}; transition: all 0.25s ease;">
                        Katalog
                    </a>

                    <a href="{{ route('rias.index') }}"
                       class="nav-item nav-link fw-semibold px-3 py-2 rounded-pill {{ request()->routeIs('rias.index') ? 'active' : '' }}"
                       style="color: {{ request()->routeIs('rias.index') ? '#8b5e3c' : '#6f4e37' }}; background: {{ request()->routeIs('rias.index') ? 'linear-gradient(90deg, rgba(216,184,146,0.16), rgba(216,184,146,0.06))' : 'transparent' }}; transition: all 0.25s ease;">
                        Jasa Rias
                    </a>

                    <a href="{{ route('recommendation.index') }}"
                       class="nav-item nav-link fw-semibold px-3 py-2 rounded-pill {{ request()->routeIs('recommendation.index') || request()->routeIs('recommendation.process') ? 'active' : '' }}"
                       style="color: {{ request()->routeIs('recommendation.index') || request()->routeIs('recommendation.process') ? '#8b5e3c' : '#6f4e37' }}; background: {{ request()->routeIs('recommendation.index') || request()->routeIs('recommendation.process') ? 'linear-gradient(90deg, rgba(216,184,146,0.16), rgba(216,184,146,0.06))' : 'transparent' }}; transition: all 0.25s ease;">
                        Rekomendasi Paket
                    </a>

                    <a href="#footer"
                       class="nav-item nav-link fw-semibold px-3 py-2 rounded-pill"
                       style="color: #6f4e37; transition: all 0.25s ease;">
                        Kontak
                    </a>
                </div>

                <div class="d-none d-xl-flex align-items-center ms-xl-3">
                    <a href="{{ route('cart.index') }}"
                       class="btn position-relative d-flex align-items-center justify-content-center rounded-pill px-4 py-2"
                       style="background: #fff; color: #8b5e3c; border: 1px solid rgba(216,184,146,0.45); box-shadow: 0 6px 16px rgba(139,94,60,0.08); min-width: 56px;">
                        <i class="fa fa-cart-shopping"></i>
                        @if($cartCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                  style="font-size: 0.65rem; min-width: 18px; height: 18px; line-height: 18px; padding: 0 5px;">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>
                </div>
            </div>
        </nav>
    </div>
</div>
