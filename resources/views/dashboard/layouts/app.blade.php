<!DOCTYPE html>
<html lang="{{ getLanguage() }}">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Good Store Dashboard Proudly Developed By Mix Code Team">
    <meta name="author" content="Mix Code Team">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- language -->
    <meta name="language" content="{{ getLanguage() }}">

    <!-- Title -->
    <title>@yield('section', 'Dashboard') | {{ config('app.name') }}</title>

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/assets/favicon/apple-touch-icon.png') }} ">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/assets/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/assets/favicon/favicon-16x16.png') }}">
    <link rel="shortcut icon" href="{{ asset('/assets/favicon/favicon.ico') }}">

    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">



    <!-- Custom fonts for this template-->
    <link href="{{ asset('/dashboard_assets/libs/fontawesome/css/all.min.css') }}" rel="stylesheet" type="text/css">
    {{--
	<link
		href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
		rel="stylesheet"> --}}


    <!-- Custom styles for this template-->
    <link href="{{ asset('/dashboard_assets/css/app.min' . getRtlDirection() . '.css') }}" rel="stylesheet">
    <link href="{{ asset('/dashboard_assets/css/custom.css') }}?v=ICnAyuQOda" rel="stylesheet">


    <link href="{{ asset('/dashboard_assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/dashboard_assets/libs/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet"
        type="text/css" />

    @livewireStyles

    <style>
        .images-container {
            height: 100%;
            width: 100%;
            position: relative;
            border: 2px #e2e2e2 solid;
            padding: 20px 20px 20px 20px;
            margin: 29px;
        }

        img.img-preview {
            height: 150px;
        }

        .image-container {
            position: relative;
        }

        a.delete-icon {
            position: absolute;
            border: 1px solid;
            top: 0;
        }
    </style>
    @yield('styles')

</head>

<body id="page-top" dir="{{ getDirection() }}" class="sidebar-toggled">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        @if (auth()->user()->isAdmin())
            @include('dashboard.layouts.includes._sidebar')
        @elseif (auth()->user()->isCompany() ||
                auth()->user()->isCustomer())
            @include('dashboard.layouts.includes._company_sidebar')
        @endif

        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                @include('dashboard.layouts.includes._header')
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid mb-5">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <i class="fas fa-bomb"></i>
                            @lang('main.errors_in_data')
                        </div>
                    @endif

                    @yield('content')

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            @include('dashboard.layouts.includes._footer')
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    @include('dashboard.layouts.includes._logoutModal')

    @livewireScripts

    {{-- Sweet Alert --}}
    @include('sweetalert::alert')



    @stack('before_scripts')

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('/dashboard_assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('/dashboard_assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('/dashboard_assets/libs/jquery-easing/jquery.easing.min.js') }}"></script>
    {{-- Select2 --}}
    <script src="{{ asset('/dashboard_assets/libs/select2/js/select2.min.js') }}" type="text/javascript"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('/dashboard_assets/js/app.min.js') }}"></script>
    <script src="{{ asset('/dashboard_assets/js/custom.js') }}"></script>
    <script src="{{ asset('/dashboard_assets/js/utils/ajaxCalls.js') }}"></script>


    <script>
        $(document).ready(function() {
            $("#active_country_id").change(function() {
                var url = $(this).find(':selected').data('link');
                window.location = url;
            });
        });
    </script>

    @yield('scripts')

    @stack('scripts')

</body>

</html>
