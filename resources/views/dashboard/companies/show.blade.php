@extends('dashboard.layouts.app')

@section('section', $sectionName)

@section('styles')
    <style>
        .map-container {
            height: 500px;
        }

        .grid-col {
            flex: 1;
            padding: 0 .1em;
        }

        form {
            margin: 15px;
        }
    </style>

    <link href="{{ asset('/dashboard_assets/libs/bootstrap-switch/css/bootstrap-switch' . getRtlDirection() . '.min.css') }}"
        rel="stylesheet" type="text/css" />

    {{-- Gijgo Picker --}}
    <link href="{{ asset('/dashboard_assets/libs/gijgo-bootstrap-pickers/css/gijgo.min.css') }}" rel="stylesheet"
        type="text/css" />


@endsection

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">

        <div>
            @admin
                @if ($company->isActive())
                    <a href="{{ route('dashboard.companies.mark_as.pending', $company) }}"
                        class="d-sm-inline-block btn btn-sm btn-warning shadow-sm">
                        <i class="fas fa-clock fa-sm text-white-50"></i>
                        @lang('main.disable_account')
                    </a>
                @else
                    <a href="{{ route('dashboard.companies.mark_as.active', $company) }}"
                        class="d-sm-inline-block btn btn-sm btn-success shadow-sm">
                        <i class="fas fa-calendar fa-sm text-white-50"></i>
                        @lang('main.mark_as_active')
                    </a>
                @endif
            @endadmin

            <a target="_new" href="{{ route('dashboard.products.index', ['user' => $company]) }}"
                class="d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-list fa-sm text-white-50"></i>
                @lang('main.show') @lang('main.products') ({{ $company->products->count() }})
            </a>
            <a target="_new" href="{{ route('dashboard.orders.companies_orders', ['user' => $company]) }}"
                class="d-sm-inline-block btn btn-sm btn-dark shadow-sm">
                <i class="fas fa-list fa-sm text-white-50"></i>
                @lang('main.show') @lang('main.orders') ({{ $company->ownedOrders->count() }})
            </a>

            <a href="{{ route('dashboard.companies.edit', $company) }}"
                class="d-sm-inline-block btn btn-sm btn-info shadow-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i>
                @lang('main.edit')
            </a>

            <a href="{{ route('dashboard.companies.destroy', $company) }}"
                class="d-sm-inline-block btn btn-sm btn-danger shadow-sm" data-toggle="modal"
                data-target="#deleteModel-{{ $company->id }}" title="@lang('main.delete')">
                <i class="fas fa-trash fa-sm text-white-50"></i>
                @lang('main.delete')
            </a>

            <a href="{{ route('dashboard.companies.create') }}" class="d-sm-inline-block btn btn-sm btn-success shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i>
                @lang('main.add') @lang('main.company')
            </a>

            <a href="{{ route('dashboard.companies.index') }}" class="d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-users fa-sm text-white-50"></i>
                @lang('main.show_all') @lang('main.companies')
            </a>

            @component('dashboard.components.deleteModelForm')
                @slot('id', $company->id)
                @slot('deleteTitle', trans('main.company') . ' ' . $company->full_name)
                @slot('url', route('dashboard.companies.destroy', $company->id))
            @endcomponent

        </div>
    </div>

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $sectionName }}</h1>

    </div>


    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-1 col-md-1"></div>
        <div class="col-xl-12 col-md-12 col-sm-12">
            <div class="card mb-3">
                <div class="card-body border-left-info">
                    <div class="card-title font-weight-bold h5 text-center  row">
                        <div class="card-title col-md-6 col-sm-12 mb-0">@lang('main.created_at'):
                            {{ $company->created_at->calendar() }} </div>
                        <div class="card-title col-md-6 col-sm-12 mb-0">@lang('main.updated_at'):
                            {{ $company->updated_at->calendar() }} </div>
                    </div>
                    <hr>
                    <div class="card-text">
                        <div class="row">
                            @include('dashboard.companies.includes.show._basic_info')
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>


    <!-- categorizations Content Row -->
    {{-- <div class="row">
    <div class="col-xl-1 col-md-1"></div>
    <div class="col-xl-12 col-md-12 col-sm-12">
        <div class="card mb-3">
            <div class="card-body border-left-warning">
                <div class="card-title font-weight-bold h4">@lang('main.categorizations')</div>
                <hr>
                <div class="card-text">
                    <div class="row">
                        @include('dashboard.companies.includes.show._categorizations')
                    </div>
                </div>

            </div>
        </div>
    </div>
</div> --}}

    <!-- Files Content Row -->
    <div class="row">
        <div class="col-xl-1 col-md-1"></div>
        <div class="col-xl-12 col-md-12 col-sm-12">
            <div class="card mb-3">
                <div class="card-body border-left-primary">
                    <div class="card-title font-weight-bold h4">@lang('main.files')</div>
                    <hr>
                    <div class="card-text">
                        <div class="row">
                            @php
                                $media = $company->UserMedia();
                            @endphp

                            @include('dashboard.companies.includes.show._files_info')
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- location Content Row -->
    <div class="row">
        <div class="col-xl-1 col-md-1"></div>
        <div class="col-xl-12 col-md-12 col-sm-12">
            <div class="card mb-3">
                <div class="card-body border-left-danger">
                    <div class="card-title font-weight-bold h4">@lang('main.address')</div>
                    <hr>
                    <div class="card-text">
                        <div class="row">
                            @include('dashboard.companies.includes.show._location_info')
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-1 col-md-1"></div>
        <div class="col-xl-12 col-md-12 col-sm-12">
            <div class="card mb-3">
                <div class="card-body border-left-success">

                    <div class="card-text">
                        <div class="row">
                            <!-- Page Heading -->
                            <div class="col-xl-12 col-md-12 mb-3">
                                <h1 class="h4 font-weight-bold mb-0 text-gray-800">@lang('main.order_reports')</h1>
                            </div>
                            @include('dashboard.companies.includes.show._order_report_info')

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Work Times --}}

    <div class="row">
        <div class="col-xl-1 col-md-1"></div>
        <div class="col-xl-12 col-md-12 col-sm-12">
            <div class="card mb-3">
                <div class="card-body border-left-warning">

                    <div class="card-text">
                        <div class="row">
                            <!-- Page Heading -->
                            <div class="col-xl-12 col-md-12 mb-3">
                                <h1 class="h4 font-weight-bold mb-0 text-gray-800">@lang('main.work_times')</h1>
                            </div>
                            @include('dashboard.companies.includes.show._work_times')

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('scripts')

    {{-- Colcade --}}
    <script src="{{ asset('/dashboard_assets/libs/colcade/colcade.js') }}" type="text/javascript"></script>


    {{-- Bootstrap Switch --}}
    <script src="{{ asset('/dashboard_assets/libs/bootstrap-switch/js/bootstrap-switch.js') }}" type="text/javascript">
    </script>

    {{-- Gijgo Picker --}}
    <script src="{{ asset('/dashboard_assets/libs/gijgo-bootstrap-pickers/js/gijgo.min.js') }}" type="text/javascript">
    </script>

    {{-- Google Apis --}}
    <script type="text/javascript"
        src="https://maps.googleapis.com/maps/api/js?key={{config('defaults.system.google_map_key')}}&libraries=places"></script>


    {{-- Map Init --}}
    <script>
        var geoLocation = {
            lat: {{ !!$company->latitude ? $company->latitude : 0 }},
            lng: {{ !!$company->longitude ? $company->longitude : 0 }},
        };

        var map = new google.maps.Map(document.getElementById('map'), {
            center: geoLocation,
            zoom: {{ !!$company->zoom ? $company->zoom : 0 }},
        });

        var marker = new google.maps.Marker({
            position: geoLocation,
            animation: google.maps.Animation.BOUNCE,
            map: map,
            draggable: false,
            title: '{{ $company->name_by_lang }}'
        });
    </script>

@endsection
