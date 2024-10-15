@extends('dashboard.layouts.app')


@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">@lang('main.statistics')</h1>
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Page Heading -->

        <!-- customers Card -->
        {{--  <div class="col-xl-12 col-md-12 mb-4">
        <h1 class="h4 font-weight-bold mb-0 text-gray-800">@lang('main.users')</h1>
    </div>
    <div class="col-xl-12 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">@lang('main.customers')
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $customers_count }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
        <!-- drivers Card -->
        {{--    <div class="col-xl-6 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">@lang('main.drivers')
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $drivers_count }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}



        <!-- Page Heading -->
        <div class="col-xl-12 col-md-12 mb-4">
            <h2 class="h4 font-weight-bold mb-0 text-gray-800">@lang('main.products')</h2>
        </div>


        <!-- all company product Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                @lang('main.all_products')</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $company_products_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- company Published product Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                @lang('main.published_products')</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $company_published_products_count }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- company Pending product Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                @lang('main.pending_products')</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $company_pending_products_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- orders --}}
        <div class="col-xl-12 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">@lang('main.orders')
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $orders_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tag fa-2x text-secondary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection
