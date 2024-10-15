<ul class="navbar-nav bg-gradient-blue-green sidebar sidebar-dark accordion toggled" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
        {{-- <div class="sidebar-brand-img">
            <img src="{{ asset('/dashboard_assets/img/logo-white.svg') }}" alt="Mix Code Board" title="MixCode Board">
        </div>
        <div class="sidebar-brand-text mx-3">Board</div> --}}
        <div class="mx-3">{{ getSettings()->name }}</div>

        {{-- <img class='img-fluid img-thumbnail img-responsive mix-code-logo '
            src="{{ asset('/dashboard_assets/img/mixcode-board-v2-logo.svg') }}" alt=""> --}}

    </a>

    <div class="sidebar-heading">
        @lang('main.statistics')
    </div>

    <li class="nav-item {{ dashboardUrl('/') }}">
        <a class="nav-link" href="{{ dashboardUrl('/') }}">
            <i class="fas fa-tachometer-alt"></i>
            <span>@lang('main.statistics')</span>
        </a>
    </li>


    <!-- Divider -->
    <hr class="sidebar-divider">
    <!-- Heading -->
    <div class="sidebar-heading">
        @lang('main.products')
    </div>

    <!-- Nav Item product -->
    <li class="nav-item {{ active_route('dashboard.products.*') }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseproducts"
            aria-expanded="true" aria-controls="collapseproducts">
            <i class="fas fa-shopping-cart"></i>
            <span>@lang('main.products')</span>
        </a>
        <div id="collapseproducts" class="collapse" aria-labelledby="headingproducts" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ active_route('dashboard.products.create') }}"
                    href="{{ route('dashboard.products.create') }}">@lang('main.add') @lang('main.products')</a>
                <a class="collapse-item {{ active_route('dashboard.products.index') }}"
                    href="{{ route('dashboard.products.index') }}">@lang('main.show_all') @lang('main.products')</a>
                <a class="collapse-item text-danger {{ active_route('dashboard.products.trashed') }}"
                    href="{{ route('dashboard.products.trashed') }}">@lang('main.trashed')</a>
            </div>
        </div>
    </li>


    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">

        @lang('main.orders')
    </div>
 

    <!-- Nav Item - orders -->
    <li class="nav-item {{ active_route('dashboard.orders.*') }}">
        <a class="nav-link" href="{{ route('dashboard.orders.index') }}">
            <i class="fas fa-shopping-basket"></i>
            <span>@lang('main.orders')</span>
        </a>
    </li>
 


    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">


    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>