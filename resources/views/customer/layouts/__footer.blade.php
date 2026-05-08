<div id="footer" class="container-fluid footer footer-brand pt-5 mt-5">
    <div class="container py-5">
        <div class="pb-4 mb-4 footer-divider">
            <div class="row g-4 align-items-center">
                <div class="col-lg-6">
                    <a href="{{ route('home') }}" class="text-decoration-none">
                        <h1 class="mb-0 footer-title">Quin Salon</h1>
                        <p class="mb-0 footer-subtitle">Persewaan Baju Adat & Jasa Rias untuk Momen Spesial Anda</p>
                    </a>
                </div>

                <div class="col-lg-6">
                    <div class="d-flex justify-content-lg-end justify-content-start pt-3 gap-2">
                        <a class="btn btn-md-square rounded-circle footer-social" href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                        <a class="btn btn-md-square rounded-circle footer-social" href="#" title="TikTok"><i class="fab fa-tiktok"></i></a>
                        <a class="btn btn-md-square rounded-circle footer-social" href="#" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                        <a class="btn btn-md-square rounded-circle footer-social" href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-5">
            <div class="col-lg-4 col-md-6">
                <div class="footer-item">
                    <h4 class="mb-3 footer-heading">Mengapa Memilih Quin Salon?</h4>
                    <p class="mb-4">
                        Quin Salon membantu pelanggan memilih baju adat, aksesoris, layanan rias,
                        dan paket bundling yang sesuai kebutuhan acara dengan proses yang lebih praktis,
                        terarah, dan mudah diakses melalui website.
                    </p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="footer-item">
                    <h4 class="mb-3 footer-heading">Navigasi Cepat</h4>
                    <p class="mb-2"><a href="{{ route('home') }}" class="text-decoration-none footer-link">Beranda</a></p>
                    <p class="mb-2"><a href="{{ route('catalog') }}" class="text-decoration-none footer-link">Katalog</a></p>
                    <p class="mb-2"><a href="{{ route('rias.index') }}" class="text-decoration-none footer-link">Jasa Rias</a></p>
                    <p class="mb-0"><a href="{{ route('recommendation.index') }}" class="text-decoration-none footer-link">Rekomendasi Paket</a></p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="footer-item">
                    <h4 class="mb-3 footer-heading">Hubungi Kami</h4>
                    <p class="mb-2">Alamat: Quin Salon, Jombang, Jawa Timur</p>
                    <p class="mb-2">Email: admin@quinsalon.com</p>
                    <p class="mb-2">WhatsApp: 081234567890</p>
                    <p class="mb-0">Pembayaran: QRIS / Tunai</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid py-4 footer-bottom">
    <div class="container">
        <div class="row">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0 footer-bottom-text">
                <a href="{{ route('home') }}" class="text-decoration-none text-cream">
                    <i class="fas fa-copyright me-2"></i>Quin Salon
                </a>
                <span id="currentYear"></span>. All rights reserved.
            </div>
            <div class="col-md-6 my-auto text-center text-md-end footer-bottom-muted">
                Website Persewaan Baju Adat & Jasa Rias
            </div>
        </div>
    </div>
</div>
