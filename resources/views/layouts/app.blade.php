<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Will Be Added Automatically By SEOTools --}}
    {{-- <title>@yield('section', 'Home') | {{ getSettings()->name }}</title> --}}
    {{-- MINIFIED ? add true in generate method --}}
    {!! SEO::generate(true) !!}
    {{-- Favicon --}}
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('/assets/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/assets/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/assets/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('/assets/favicon/site.webmanifest') }}">
    <link rel="mask-icon" href="/{{ asset('/assets/favicon/safari-pinned-tab.svg') }}" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Montserrat:300,400,500,700"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/vendor/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/ionicons/css/ionicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/owl.carousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/venobox/venobox.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/vendor/aos/aos.css') }}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">

    @yield('styles')

</head>

<body dir="{{ getDirection() }}">
    <div id="app">

        @include('layouts.includes._header')

        @yield('content')

        @include('layouts.includes._footer')


    </div>
    {{-- Sweet Alert --}}
    @include('sweetalert::alert')


    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery.easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>
    <script src="{{ asset('assets/vendor/counterup/counterup.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/owl.carousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/waypoints/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/venobox/venobox.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/aos/aos.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    @yield('scripts')

    @yield('after_scripts')


</body>

</html>
