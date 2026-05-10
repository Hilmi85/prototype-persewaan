<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Quin Salon</title>

    <link rel="stylesheet" crossorigin="" href="{{ asset('assets/admin/compiled/css/app.css') }}">
    <link rel="stylesheet" crossorigin="" href="{{ asset('assets/admin/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" crossorigin="" href="{{ asset('assets/admin/compiled/css/auth.css') }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <style>
        :root {
            --primary: #8b5cf6;
            --primary-dark: #6d28d9;
            --secondary: #ec4899;
            --soft-bg: #f8f5ff;
            --text-dark: #2d2341;
            --text-muted: #7c6f91;
            --white: #ffffff;
        }

        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background:
                radial-gradient(circle at top left, rgba(236, 72, 153, 0.18), transparent 32%),
                radial-gradient(circle at bottom right, rgba(139, 92, 246, 0.22), transparent 35%),
                linear-gradient(135deg, #fff7fb 0%, #f7f2ff 45%, #ffffff 100%);
        }

        .login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 18px;
            position: relative;
            overflow: hidden;
        }

        .login-page::before,
        .login-page::after {
            content: '';
            position: absolute;
            border-radius: 999px;
            filter: blur(2px);
            opacity: 0.55;
            z-index: 0;
        }

        .login-page::before {
            width: 280px;
            height: 280px;
            background: rgba(236, 72, 153, 0.18);
            top: -80px;
            left: -70px;
        }

        .login-page::after {
            width: 340px;
            height: 340px;
            background: rgba(139, 92, 246, 0.18);
            bottom: -120px;
            right: -100px;
        }

        .login-wrapper {
            width: 100%;
            max-width: 1120px;
            min-height: 650px;
            background: rgba(255, 255, 255, 0.82);
            backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.85);
            border-radius: 34px;
            box-shadow: 0 30px 90px rgba(80, 49, 130, 0.16);
            overflow: hidden;
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: 1.05fr 0.95fr;
        }

        .login-hero {
            padding: 48px;
            color: #ffffff;
            position: relative;
            overflow: hidden;
            background:
                linear-gradient(135deg, rgba(109, 40, 217, 0.94), rgba(236, 72, 153, 0.9)),
                radial-gradient(circle at 20% 20%, rgba(255, 255, 255, 0.32), transparent 30%);
        }

        .login-hero::before {
            content: '';
            position: absolute;
            width: 360px;
            height: 360px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.14);
            right: -130px;
            top: -130px;
        }

        .login-hero::after {
            content: '';
            position: absolute;
            width: 250px;
            height: 250px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.22);
            left: -70px;
            bottom: -80px;
        }

        .brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.16);
            border: 1px solid rgba(255, 255, 255, 0.24);
            color: #ffffff;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .brand-logo {
            width: 34px;
            height: 34px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.95);
            color: var(--primary-dark);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            box-shadow: 0 10px 24px rgba(40, 20, 80, 0.16);
        }

        .hero-content {
            position: relative;
            z-index: 1;
            margin-top: 88px;
        }

        .hero-title {
            font-size: clamp(2.2rem, 4vw, 4.2rem);
            line-height: 1.05;
            font-weight: 900;
            letter-spacing: -1.8px;
            margin-bottom: 22px;
        }

        .hero-desc {
            max-width: 520px;
            color: rgba(255, 255, 255, 0.86);
            font-size: 1.05rem;
            line-height: 1.8;
            margin-bottom: 32px;
        }

        .hero-features {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
            max-width: 520px;
        }

        .feature-card {
            padding: 16px;
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.18);
            backdrop-filter: blur(14px);
        }

        .feature-card i {
            font-size: 1.3rem;
            margin-bottom: 8px;
            display: inline-block;
        }

        .feature-card h6 {
            color: #ffffff;
            margin-bottom: 4px;
            font-weight: 800;
        }

        .feature-card p {
            margin: 0;
            color: rgba(255, 255, 255, 0.76);
            font-size: 0.86rem;
            line-height: 1.5;
        }

        .login-form-section {
            padding: 54px 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.72);
        }

        .login-card {
            width: 100%;
            max-width: 430px;
        }

        .mobile-brand {
            display: none;
            align-items: center;
            gap: 12px;
            margin-bottom: 28px;
        }

        .mobile-brand .brand-logo {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #ffffff;
        }

        .login-title {
            color: var(--text-dark);
            font-weight: 900;
            font-size: 2.25rem;
            letter-spacing: -1px;
            margin-bottom: 10px;
        }

        .login-subtitle {
            color: var(--text-muted);
            line-height: 1.7;
            margin-bottom: 32px;
        }

        .alert-custom {
            border: 0;
            border-radius: 18px;
            padding: 14px 16px;
            background: #fff1f2;
            color: #be123c;
            font-size: 0.92rem;
            margin-bottom: 22px;
        }

        .form-label {
            color: var(--text-dark);
            font-weight: 700;
            margin-bottom: 8px;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 20px;
        }

        .input-icon {
            position: absolute;
            top: 50%;
            left: 18px;
            transform: translateY(-50%);
            color: var(--primary);
            font-size: 1.12rem;
            z-index: 2;
        }

        .form-control-custom {
            width: 100%;
            min-height: 58px;
            border-radius: 18px;
            border: 1px solid #eadff8;
            background: #ffffff;
            color: var(--text-dark);
            padding: 15px 48px 15px 50px;
            font-size: 0.98rem;
            transition: 0.2s ease;
            box-shadow: 0 10px 28px rgba(109, 40, 217, 0.04);
        }

        .form-control-custom:focus {
            border-color: rgba(139, 92, 246, 0.75);
            box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.12);
            outline: none;
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 16px;
            transform: translateY(-50%);
            border: 0;
            background: transparent;
            color: #9a8baa;
            font-size: 1.1rem;
            cursor: pointer;
            z-index: 3;
        }

        .password-toggle:hover {
            color: var(--primary);
        }

        .invalid-feedback-custom {
            color: #dc2626;
            font-size: 0.86rem;
            margin-top: 7px;
            display: block;
        }

        .remember-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 14px;
            margin-bottom: 28px;
            color: var(--text-muted);
            font-size: 0.93rem;
        }

        .remember-row a {
            color: var(--primary-dark);
            font-weight: 700;
            text-decoration: none;
        }

        .remember-row a:hover {
            text-decoration: underline;
        }

        .btn-login {
            width: 100%;
            min-height: 58px;
            border: 0;
            border-radius: 18px;
            background: linear-gradient(135deg, var(--primary-dark), var(--secondary));
            color: #ffffff;
            font-weight: 900;
            font-size: 1rem;
            box-shadow: 0 18px 38px rgba(139, 92, 246, 0.28);
            transition: 0.25s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 22px 45px rgba(139, 92, 246, 0.36);
        }

        .login-footer {
            margin-top: 28px;
            padding: 16px;
            border-radius: 18px;
            background: var(--soft-bg);
            color: var(--text-muted);
            font-size: 0.88rem;
            line-height: 1.6;
            text-align: center;
        }

        @media (max-width: 991px) {
            .login-wrapper {
                grid-template-columns: 1fr;
                max-width: 540px;
                min-height: auto;
            }

            .login-hero {
                display: none;
            }

            .login-form-section {
                padding: 40px 26px;
            }

            .mobile-brand {
                display: flex;
            }

            .login-title {
                font-size: 1.9rem;
            }
        }

        @media (max-width: 480px) {
            .login-page {
                padding: 18px 12px;
            }

            .login-wrapper {
                border-radius: 26px;
            }

            .login-form-section {
                padding: 32px 20px;
            }

            .remember-row {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <script src="{{ asset('assets/admin/static/js/initTheme.js') }}"></script>

    <main class="login-page">
        <section class="login-wrapper">
            <div class="login-hero">
                <div class="brand-badge">
                    <span class="brand-logo">Q</span>
                    <span>Quin Salon Admin</span>
                </div>

                <div class="hero-content">
                    <h1 class="hero-title">
                        Kelola Persewaan dengan Lebih Mudah
                    </h1>

                    <p class="hero-desc">
                        Panel admin Quin Salon membantu mengelola data customer, item sewa,
                        booking, pembayaran, pengembalian, dan laporan secara lebih rapi.
                    </p>

                    <div class="hero-features">
                        <div class="feature-card">
                            <i class="bi bi-bag-check"></i>
                            <h6>Manajemen Order</h6>
                            <p>Pantau pesanan customer dari awal sampai selesai.</p>
                        </div>

                        <div class="feature-card">
                            <i class="bi bi-calendar2-check"></i>
                            <h6>Booking Sewa</h6>
                            <p>Kelola jadwal sewa dan pengembalian dengan jelas.</p>
                        </div>

                        <div class="feature-card">
                            <i class="bi bi-credit-card"></i>
                            <h6>Pembayaran</h6>
                            <p>Kontrol status pembayaran tunai maupun QRIS.</p>
                        </div>

                        <div class="feature-card">
                            <i class="bi bi-box-seam"></i>
                            <h6>Stok Item</h6>
                            <p>Monitor item, varian, bundle, dan ketersediaan stok.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="login-form-section">
                <div class="login-card">
                    <div class="mobile-brand">
                        <span class="brand-logo">Q</span>
                        <div>
                            <strong>Quin Salon</strong>
                            <div style="font-size: 0.85rem; color: var(--text-muted);">
                                Admin Panel
                            </div>
                        </div>
                    </div>

                    <h2 class="login-title">Selamat Datang</h2>
                    <p class="login-subtitle">
                        Silakan masuk menggunakan akun admin untuk mengelola sistem persewaan Quin Salon.
                    </p>

                    @if ($errors->any())
                        <div class="alert-custom">
                            <strong>Login gagal.</strong>
                            <div>Periksa kembali email dan password yang kamu masukkan.</div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Admin</label>
                            <div class="input-group-custom">
                                <i class="bi bi-envelope input-icon"></i>
                                <input
                                    id="email"
                                    type="email"
                                    class="form-control-custom @error('email') is-invalid @enderror"
                                    placeholder="Masukkan email admin"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    autofocus
                                    autocomplete="email">
                            </div>

                            @error('email')
                                <span class="invalid-feedback-custom">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group-custom">
                                <i class="bi bi-shield-lock input-icon"></i>
                                <input
                                    id="password"
                                    type="password"
                                    class="form-control-custom @error('password') is-invalid @enderror"
                                    placeholder="Masukkan password"
                                    name="password"
                                    required
                                    autocomplete="current-password">

                                <button class="password-toggle" type="button" onclick="togglePassword()">
                                    <i id="passwordIcon" class="bi bi-eye"></i>
                                </button>
                            </div>

                            @error('password')
                                <span class="invalid-feedback-custom">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="remember-row">
                            <label class="d-flex align-items-center gap-2 mb-0">
                                <input
                                    type="checkbox"
                                    name="remember"
                                    class="form-check-input mt-0">
                                <span>Ingat saya</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}">Lupa password?</a>
                            @endif
                        </div>

                        <button type="submit" class="btn-login">
                            Masuk ke Dashboard
                        </button>
                    </form>

                    <div class="login-footer">
                        Akses halaman ini hanya untuk admin Quin Salon.
                        Pastikan akun dan password tidak dibagikan kepada pihak lain.
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('passwordIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('bi-eye');
                passwordIcon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('bi-eye-slash');
                passwordIcon.classList.add('bi-eye');
            }
        }
    </script>
</body>
</html>
