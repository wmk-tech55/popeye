@extends('dashboard.layouts.app')


@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">@lang('main.statistics')</h1>
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Page Heading -->
        <div class="col-xl-12 col-md-12 mb-4">
            <h1 class="h4 font-weight-bold mb-0 text-gray-800">@lang('main.users')</h1>
        </div>

        <!-- admins Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">@lang('main.admins')
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $admins_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-cog fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- customers Card -->
        <div class="col-xl-3 col-md-6 mb-4">
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
        </div>

        <!-- drivers Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">@lang('main.drivers')
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $drivers_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-truck fa-2x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- comanies Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">@lang('main.companies')
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $companies_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-store fa-2x text-success"></i>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

 

        <!-- Page Heading -->
        <div class="col-xl-12 col-md-12 mb-4">
            <h2 class="h4 font-weight-bold mb-0 text-gray-800">@lang('main.categorizations')</h2>
        </div>

        <!--   categorizations Card -->
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                @lang('main.categorizations')</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $categorizations_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tag fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
          <!--   Categories Card -->
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                @lang('main.categories')</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $categories_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tag fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- Page Heading -->
        <div class="col-xl-12 col-md-12 mb-4">
            <h2 class="h4 font-weight-bold mb-0 text-gray-800">@lang('main.products')</h2>
        </div>

        <!-- admin product Card -->
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">@lang('main.all_products')
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $all_products_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-store fa-2x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- comnapny product Card -->
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-dark shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                @lang('main.companies_products')
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $other_products_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-store fa-2x text-dark"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin Published product Card -->
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                @lang('main.published_products')</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $admin_published_products_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin Pending product Card -->
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                @lang('main.pending_products')</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $admin_pending_products_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Page Heading -->
        <div class="col-xl-12 col-md-12 mb-4">
            <h2 class="h4 font-weight-bold mb-0 text-gray-800">@lang('main.orders')</h2>
        </div>


        <!-- orders Card -->
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">@lang('main.my_orders')
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $my_orders_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tag fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- companies orders Card -->
        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                @lang('main.companies_orders')
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $other_orders_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tag fa-2x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Page Heading -->
        <div class="col-xl-12 col-md-12 mb-4">
            <h2 class="h4 font-weight-bold mb-0 text-gray-800">@lang('main.contacts')</h2>
        </div>

        <!-- Messages Card -->
        <div class="col-xl-12 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">@lang('main.contacts')
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $messages_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-envelope fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- <div class="col-xl-12 col-md-12 mb-4">
        <div class="card">
            <div class="card-body text-center text-info">
                Statistics Comming Soon
            </div>
        </div>
    </div> --}}

    </div>
@endsection
