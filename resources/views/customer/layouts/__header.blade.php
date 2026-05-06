<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Quin Salon')</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Persewaan baju adat dan jasa rias Quin Salon" name="keywords">
    <meta content="Website persewaan baju adat, jasa rias, dan rekomendasi paket Quin Salon" name="description">

    <link rel="icon" type="image/png" href="{{ asset('assets/customer/img/favicon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

    <link href="https://use.fontawesome.com/releases/v6.5.2/css/all.css" rel="stylesheet">
    <link href="{{ asset('assets/customer/lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/customer/lib/lightbox/css/lightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/customer/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/customer/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/customer/css/style.css') }}" rel="stylesheet">

    @stack('styles')
</head>
