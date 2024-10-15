<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow-sm">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    {{-- <a class="navbar-brand custom-navbar-brand text-muted font-weight-bold" href="{{ route('dashboard.dashboard') }}">
        @if (isLang('ar'))
            @lang('main.mx_board') <sub> @lang('main.mx_namespace App\Http') </sub>
        @else
            @lang('main.mx_namespace App\Http') <sub> @lang('main.mx_board') </sub>
            
        @endif
    </a> --}}
    {{-- <a class="navbar-brand custom-navbar-brand" href="{{ route('dashboard.dashboard') }}">
        <img  loading="lazy"src="{{ asset('/dashboard_assets/img/namespace App\Http-board-v2-logo.svg') }}" alt="">
    </a> --}}


    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">



        <!-- Nav Item - Notifications -->
        @include('dashboard.layouts.includes._header_notifications')

        @admin
            <div class="topbar-divider d-none d-sm-block"></div>
            @include('dashboard.layouts.includes._statistics')



            <div class="topbar-divider d-none d-sm-block"></div>

            @include('dashboard.layouts.includes._site_settings')
            <div class="topbar-divider d-none d-sm-block"></div>
            <!-- Nav Item - countries -->
            @include('dashboard.layouts.includes._fetch_all_countries')
        @endadmin

        <div class="topbar-divider d-none d-sm-block"></div>
        <!-- Nav Item - languages -->
        @include('dashboard.layouts.includes._header_languages')


        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        @include('dashboard.layouts.includes._header_user_info')


    </ul>

</nav>
