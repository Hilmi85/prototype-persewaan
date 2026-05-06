<div id="footer" class="container-fluid footer pt-5 mt-5" style="background: linear-gradient(135deg, #4b3228, #2f1f19); color: rgba(255,255,255,0.75);">
    <div class="container py-5">
        <div class="pb-4 mb-4" style="border-bottom: 1px solid rgba(216, 184, 146, 0.45);">
            <div class="row g-4">
                <div class="col-lg-6">
                    <a href="{{ route('home') }}" class="text-decoration-none">
                        <h1 class="mb-0" style="color: #f3d2a2;">Quin Salon</h1>
                        <p class="mb-0" style="color: #e7c79f;">Persewaan Baju Adat & Jasa Rias untuk Momen Spesial Anda</p>
                    </a>
                </div>

                <div class="col-lg-6">
                    <div class="d-flex justify-content-end pt-3">
                        <a class="btn me-2 btn-md-square rounded-circle"
                           style="border: 1px solid #d8b892; color: #f3d2a2;"
                           href="#"
                           title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a class="btn me-2 btn-md-square rounded-circle"
                           style="border: 1px solid #d8b892; color: #f3d2a2;"
                           href="#"
                           title="TikTok">
                            <i class="fab fa-tiktok"></i>
                        </a>
                        <a class="btn me-2 btn-md-square rounded-circle"
                           style="border: 1px solid #d8b892; color: #f3d2a2;"
                           href="#"
                           title="WhatsApp">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a class="btn btn-md-square rounded-circle"
                           style="border: 1px solid #d8b892; color: #f3d2a2;"
                           href="#"
                           title="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-5">
            <div class="col-lg-4 col-md-6">
                <div class="footer-item">
                    <h4 class="mb-3" style="color: #fff;">Mengapa Memilih Quin Salon?</h4>
                    <p class="mb-4">
                        Quin Salon membantu pelanggan memilih baju adat, aksesoris, layanan rias,
                        dan paket bundling yang sesuai kebutuhan acara dengan proses yang lebih praktis,
                        terarah, dan mudah diakses melalui website.
                    </p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="footer-item">
                    <h4 class="mb-3" style="color: #fff;">Navigasi Cepat</h4>
                    <p class="mb-2"><a href="{{ route('home') }}" class="text-decoration-none" style="color: rgba(255,255,255,0.75);">Beranda</a></p>
                    <p class="mb-2"><a href="{{ route('catalog') }}" class="text-decoration-none" style="color: rgba(255,255,255,0.75);">Katalog</a></p>
                    <p class="mb-2"><a href="{{ route('rias.index') }}" class="text-decoration-none" style="color: rgba(255,255,255,0.75);">Jasa Rias</a></p>
                    <p class="mb-0"><a href="{{ route('recommendation.index') }}" class="text-decoration-none" style="color: rgba(255,255,255,0.75);">Rekomendasi Paket</a></p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="footer-item">
                    <h4 class="mb-3" style="color: #fff;">Hubungi Kami</h4>
                    <p class="mb-2">Alamat: Quin Salon, Jombang, Jawa Timur</p>
                    <p class="mb-2">Email: admin@quinsalon.com</p>
                    <p class="mb-2">WhatsApp: 081234567890</p>
                    <p class="mb-0">Pembayaran: QRIS / Tunai</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid py-4" style="background-color: #241712;">
    <div class="container">
        <div class="row">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <span style="color: rgba(255,255,255,0.8);">
                    <a href="{{ route('home') }}" class="text-decoration-none" style="color: #f3d2a2;">
                        <i class="fas fa-copyright me-2" style="color: #f3d2a2;"></i>Quin Salon
                    </a>
                    <span id="currentYear"></span>. All rights reserved.
                </span>
            </div>
            <div class="col-md-6 my-auto text-center text-md-end" style="color: rgba(255,255,255,0.65);">
                Website Persewaan Baju Adat & Jasa Rias
            </div>
        </div>
    </div>
</div>
