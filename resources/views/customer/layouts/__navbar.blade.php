<div class="container-fluid fixed-top shadow-sm" style="background-color: rgba(255, 250, 245, 0.97); backdrop-filter: blur(6px);">
    <div class="container px-0">
        <nav class="navbar navbar-expand-xl" style="min-height: 85px;">
            <a href="#" class="navbar-brand d-flex flex-column justify-content-center">
                <h2 class="mb-0 fw-bold" style="color: #8b5e3c;">Quin Salon</h2>
                <small style="color: #c8a97e; letter-spacing: 1px;">Baju Adat & Jasa Rias</small>
            </a>

            <button class="navbar-toggler py-2 px-3 border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars" style="color: #8b5e3c;"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav mx-auto gap-xl-3">
                    <a href="#" class="nav-item nav-link fw-semibold" style="color: #6f4e37;">Beranda</a>
                    <a href="/" class="nav-item nav-link active fw-semibold" style="color: #8b5e3c;">Katalog</a>
                    <a href="#" class="nav-item nav-link fw-semibold" style="color: #6f4e37;">Kontak</a>
                </div>

                <div class="d-flex align-items-center m-3 me-0">
                    <a href="{{ route('cart') }}"
                       class="position-relative my-auto d-flex align-items-center justify-content-center rounded-circle"
                       style="width: 48px; height: 48px; border: 1px solid #d8b892; color: #8b5e3c; background-color: #fff;">
                        <i class="fa fa-shopping-bag"></i>
                    </a>
                </div>
            </div>
        </nav>
    </div>
</div>
