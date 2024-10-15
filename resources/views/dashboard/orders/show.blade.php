@extends('dashboard.layouts.app')

@section('section', $sectionName)
@section('styles')

    {{-- Bootstrap Switch --}}
    <link href="{{ asset('/dashboard_assets/libs/bootstrap-switch/css/bootstrap-switch' . getRtlDirection() . '.min.css') }}"
        rel="stylesheet" type="text/css" />
    <style>
        .status-form {
            display: contents;
        }
    </style>

@endsection

@section('content')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $sectionName }}</h1>
        <div>


            <a target="_new" href="{{ route('dashboard.orders.print', $order) }}"
                class="d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-print fa-sm text-white-50"></i>
                @lang('main.print')
            </a>



            @admin
                <a href="{{ route('dashboard.orders.destroy', $order) }}"
                    class="d-sm-inline-block btn btn-sm btn-danger shadow-sm" data-toggle="modal"
                    data-target="#deleteModel-{{ $order->id }}" title="@lang('main.delete')">
                    <i class="fas fa-trash fa-sm text-white-50"></i>
                    @lang('main.delete')
                </a>
            @endadmin


            <a href="{{ route('dashboard.orders.index') }}" class="d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-paperclip fa-sm text-white-50"></i>
                @lang('main.show_all') @lang('main.orders')
            </a>

            @component('dashboard.components.deleteModelForm')
                @slot('id', $order->id)
                @slot('deleteTitle', trans('main.orders') . ' ' . $order->name)
                @slot('url', route('dashboard.orders.destroy', $order->id))
            @endcomponent

        </div>
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Page Heading -->
        <div class="col-xl-12 col-md-12 mb-3">
            <h1 class="h4 font-weight-bold mb-0 text-gray-800">@lang('main.basic')</h1>
        </div>
        {{-- Basic Info --}}
        @include('dashboard.orders.includes._customer_info')

        <!-- Page Heading -->
        <div class="col-xl-12 col-md-12 mb-3">
            <h1 class="h4 font-weight-bold mb-0 text-gray-800">@lang('main.order_details')</h1>
        </div>
        {{-- order Detailso --}} 
        @include('dashboard.orders.includes._order_details')


        <!-- Page Heading -->
        <div class="col-xl-12 col-md-12 mb-3">
            <h1 class="h4 font-weight-bold mb-0 text-gray-800">@lang('main.order_status')</h1>
        </div>
        {{-- order status --}}
        <div class="col-xl-12 col-md-12 col-sm-12 mb-3">
            <div class="card border-left-danger">

                <div class="card-body">
                    <div class="card-title font-weight-bold h5 text-center   ">

                        <div class="card-text">
                            <div class="row">

                                @include('dashboard.orders.includes._order_status')


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Page Heading -->
        <div class="col-xl-12 col-md-12 mb-3">
            <h1 class="h4 font-weight-bold mb-0 text-gray-800">@lang('main.order')</h1>
        </div>
        {{-- Total Price Info --}}
        @include('dashboard.orders.includes._order_products_info')

    </div>
@endsection



@section('scripts')

    {{-- Bootstrap Switch --}}
    <script src="{{ asset('/dashboard_assets/libs/bootstrap-switch/js/bootstrap-switch.js') }}" type="text/javascript">
    </script>

    {{-- Select2 --}}

@endsection
