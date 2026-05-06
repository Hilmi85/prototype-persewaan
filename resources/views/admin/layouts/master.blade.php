@include('admin.layouts.__header')

<body>
    <script src="{{ asset('assets/admin/static/js/initTheme.js') }}"></script>

    <div id="app">
        @include('admin.layouts.__sidebar')

        <div id="main">
            <header class="mb-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <a href="#" class="burger-btn d-block d-xl-none text-decoration-none">
                        <i class="bi bi-justify fs-3"></i>
                    </a>

                    <div class="ms-auto d-flex align-items-center gap-2">
                        @hasSection('title')
                            <span class="badge bg-light text-dark border px-3 py-2">
                                @yield('title')
                            </span>
                        @endif
                    </div>
                </div>
            </header>

            <main>
                @yield('content')
            </main>

            @include('admin.layouts.__footer')
        </div>
    </div>

    <script src="{{ asset('assets/admin/static/js/components/dark.js') }}"></script>
    <script src="{{ asset('assets/admin/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/admin/compiled/js/app.js') }}"></script>

    @yield('script')
</body>
</html>
