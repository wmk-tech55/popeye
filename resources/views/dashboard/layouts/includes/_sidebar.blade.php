{{-- <ul class="navbar-nav bg-gradient-blue sidebar sidebar-dark  " id="accordionSidebar"> --}}
<ul class="navbar-nav bg-gradient-blue-green sidebar sidebar-dark  " id="accordionSidebar">

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

    <!-- Divider -->
    <hr class="sidebar-divider my-0">


    <li class="nav-item {{ active_route('dashboard.banners.*') }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBanners"
            aria-expanded="true" aria-controls="collapseBanners">
            <i class="fas fa-award"></i>
            <span>@lang('main.banners')</span>
        </a>
        <div id="collapseBanners" class="collapse" aria-labelledby="headingBanners" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ active_route('dashboard.banners.create') }}"
                    href="{{ route('dashboard.banners.create') }}">@lang('main.add') @lang('main.banners')</a>
                <a class="collapse-item {{ active_route('dashboard.banners.index') }}"
                    href="{{ route('dashboard.banners.index') }}">@lang('main.show_all') @lang('main.banners')</a>
                <a class="collapse-item text-danger {{ active_route('dashboard.banners.trashed') }}"
                    href="{{ route('dashboard.banners.trashed') }}">@lang('main.trashed')</a>
            </div>
        </div>
    </li>


    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        @lang('main.store_categorizations')
    </div>

    {{-- store_categorizations --}}
    <li class="nav-item {{ active_route('dashboard.categorizations.*') }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsecategorizations"
            aria-expanded="true" aria-controls="collapsecategorizations">
            <i class="far fa-list-alt"></i>
            <span>@lang('main.store_categorizations')</span>
        </a>
        <div id="collapsecategorizations" class="collapse" aria-labelledby="headingclassifications"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ active_route('dashboard.categorizations.create') }}"
                    href="{{ route('dashboard.categorizations.create') }}">@lang('main.add')
                    @lang('main.categorizations')</a>
                <a class="collapse-item {{ active_route('dashboard.categorizations.index') }}"
                    href="{{ route('dashboard.categorizations.index') }}">@lang('main.show_all')
                    @lang('main.categorizations')</a>
                <a class="collapse-item text-danger {{ active_route('dashboard.categorizations.trashed') }}"
                    href="{{ route('dashboard.categorizations.trashed') }}">@lang('main.trashed')</a>
            </div>
        </div>
    </li>


    <li class="nav-item {{ active_route('dashboard.categories.*') }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCategories"
            aria-expanded="true" aria-controls="collapseCategories">
            <i class="fas fa-clipboard-list"></i>
            <span>@lang('main.categories')</span>
        </a>
        <div id="collapseCategories" class="collapse" aria-labelledby="headingCategories"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ active_route('dashboard.categories.create') }}"
                    href="{{ route('dashboard.categories.create') }}">@lang('main.add') @lang('main.categories')</a>
                <a class="collapse-item {{ active_route('dashboard.categories.index') }}"
                    href="{{ route('dashboard.categories.index') }}">@lang('main.show_all') @lang('main.categories')</a>
                <a class="collapse-item text-danger {{ active_route('dashboard.categories.trashed') }}"
                    href="{{ route('dashboard.categories.trashed') }}">@lang('main.trashed')</a>
            </div>
        </div>
    </li>




    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        @lang('main.users')
    </div>
    <!-- Nav Item Users -->
    <li class="nav-item {{ active_route('dashboard.users.*') }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUsers"
            aria-expanded="true" aria-controls="collapseUsers">
            <i class="fas fa-fw fa-users"></i>
            <span>@lang('main.users')</span>
        </a>
        <div id="collapseUsers" class="collapse" aria-labelledby="headingUsers" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ active_route('dashboard.users.create') }}"
                    href="{{ route('dashboard.users.create') }}">@lang('main.add') @lang('main.users')</a>
                <a class="collapse-item {{ active_route('dashboard.users.index') }}"
                    href="{{ route('dashboard.users.index') }}">@lang('main.show_all') @lang('main.users')</a>
                <a class="collapse-item text-danger {{ active_route('dashboard.users.trashed') }}"
                    href="{{ route('dashboard.users.trashed') }}">@lang('main.trashed')</a>
            </div>
        </div>
    </li>


    <!-- Nav Item companies -->
    <li class="nav-item {{ active_route('dashboard.companies.*') }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCompanies"
            aria-expanded="true" aria-controls="collapseCompanies">
            <i class="fas fa-fw fa-store"></i>
            <span>@lang('main.companies')</span>
        </a>
        <div id="collapseCompanies" class="collapse" aria-labelledby="headingCompanies" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ active_route('dashboard.companies.create') }}"
                    href="{{ route('dashboard.companies.create') }}">@lang('main.add') @lang('main.companies')</a>
                <a class="collapse-item {{ active_route('dashboard.companies.index') }}"
                    href="{{ route('dashboard.companies.index') }}">@lang('main.show_all') @lang('main.companies')</a>
                <a class="collapse-item text-danger {{ active_route('dashboard.companies.trashed') }}"
                    href="{{ route('dashboard.companies.trashed') }}">@lang('main.trashed')</a>
            </div>
        </div>
    </li>

    <!-- Nav Item drivers -->
    <li class="nav-item {{ active_route('dashboard.drivers.*') }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseDrivers"
            aria-expanded="true" aria-controls="collapseDrivers">
            <i class="fas fa-fw fa-truck"></i>
            <span>@lang('main.drivers')</span>
        </a>
        <div id="collapseDrivers" class="collapse" aria-labelledby="headingDrivers" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ active_route('dashboard.drivers.create') }}"
                    href="{{ route('dashboard.drivers.create') }}">@lang('main.add') @lang('main.drivers')</a>
                <a class="collapse-item {{ active_route('dashboard.drivers.index') }}"
                    href="{{ route('dashboard.drivers.index') }}">@lang('main.show_all') @lang('main.drivers')</a>
                <a class="collapse-item text-danger {{ active_route('dashboard.drivers.trashed') }}"
                    href="{{ route('dashboard.drivers.trashed') }}">@lang('main.trashed')</a>
            </div>
        </div>
    </li>




    <hr class="sidebar-divider">



    {{--     <li class="nav-item {{ active_route('dashboard.classifications.*') }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseClassifications"
            aria-expanded="true" aria-controls="collapseClassifications">
            <i class="fas fa-list-ul"></i>
            <span>@lang('main.classifications')</span>
        </a>
        <div id="collapseClassifications" class="collapse" aria-labelledby="headingclassifications"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ active_route('dashboard.classifications.create') }}"
                    href="{{ route('dashboard.classifications.create') }}">@lang('main.add')
                    @lang('main.sizes')</a>
                <a class="collapse-item {{ active_route('dashboard.classifications.index') }}"
                    href="{{ route('dashboard.classifications.index') }}">@lang('main.show_all')
                    @lang('main.sizes')</a>
                <a class="collapse-item text-danger {{ active_route('dashboard.classifications.trashed') }}"
                    href="{{ route('dashboard.classifications.trashed') }}">@lang('main.trashed')</a>
            </div>
        </div>
    </li> 
       <hr class="sidebar-divider">
    --}}


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
        <div id="collapseproducts" class="collapse" aria-labelledby="headingproducts"
            data-parent="#accordionSidebar">
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
    <hr class="sidebar-divider d-none d-md-block">

    <li class="nav-item {{ active_route('dashboard.orders.*') }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOrders"
            aria-expanded="true" aria-controls="collapseOrders">
            <i class="fas fa-fw fa-map-signs"></i>
            <span>@lang('main.orders')</span>
        </a>
        <div id="collapseOrders" class="collapse" aria-labelledby="headingOrders" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ active_route('dashboard.orders.index') }}"
                    href="{{ route('dashboard.orders.index') }}">@lang('main.show_all') @lang('main.my_orders')</a>
                <a class="collapse-item {{ active_route('dashboard.orders.companies_orders') }}"
                    href="{{ route('dashboard.orders.companies_orders') }}">@lang('main.show_all')
                    @lang('main.companies_orders')</a>
                <a class="collapse-item text-danger {{ active_route('dashboard.orders.trashed') }}"
                    href="{{ route('dashboard.orders.trashed') }}">@lang('main.trashed')</a>

            </div>
        </div>
    </li>

    <!-- Heading -->
    <!-- Nav Item Country -->
    <div class="sidebar-heading">
        @lang('main.location')
    </div>

    <li class="nav-item {{ active_route('dashboard.countries.*') }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCountries"
            aria-expanded="true" aria-controls="collapseCountries">
            <i class="fas fa-fw fa-globe"></i>
            <span>@lang('main.countries_and_shipping_fees')</span>
        </a>
        <div id="collapseCountries" class="collapse" aria-labelledby="headingCountries"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ active_route('dashboard.countries.create') }}"
                    href="{{ route('dashboard.countries.create') }}">@lang('main.add') @lang('main.countries')</a>
                <a class="collapse-item {{ active_route('dashboard.countries.index') }}"
                    href="{{ route('dashboard.countries.index') }}">@lang('main.show_all') @lang('main.countries')</a>
                <a class="collapse-item text-danger {{ active_route('dashboard.countries.trashed') }}"
                    href="{{ route('dashboard.countries.trashed') }}">@lang('main.trashed')</a>
            </div>
        </div>
    </li>

    <!-- Nav Item City -->
    {{-- <li class="nav-item {{ active_route('dashboard.cities.*') }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCities"
            aria-expanded="true" aria-controls="collapseCities">
            <i class="fas fa-fw fa-map-signs"></i>
            <span>@lang('main.cities')</span>
        </a>
        <div id="collapseCities" class="collapse" aria-labelledby="headingCities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ active_route('dashboard.cities.create') }}"
                    href="{{ route('dashboard.cities.create') }}">@lang('main.add') @lang('main.cities')</a>
                <a class="collapse-item {{ active_route('dashboard.cities.index') }}"
                    href="{{ route('dashboard.cities.index') }}">@lang('main.show_all') @lang('main.cities')</a>
                <a class="collapse-item text-danger {{ active_route('dashboard.cities.trashed') }}"
                    href="{{ route('dashboard.cities.trashed') }}">@lang('main.trashed')</a>
            </div>
        </div>
    </li> --}}

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">
    <!-- Nav Item Country -->
    <div class="sidebar-heading">
        @lang('main.contacts')
    </div>
    <!-- Nav Item - Contacts -->
    <li class="nav-item {{ active_route('dashboard.contacts.*') }}">
        <a class="nav-link" href="{{ route('dashboard.contacts.index') }}">
            <i class="fas fa-fw fa-envelope"></i>
            <span>@lang('main.contacts')</span>
        </a>
    </li>

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0 " id="sidebarToggle"></button>
    </div>

</ul>
