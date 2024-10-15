@extends('dashboard.layouts.print')

@section('section', $sectionName)

@section('styles')
<style>
    .w-40 {
        width: 40% !important;
    }

    .text-align-end {
        text-align: end;
    }

    hr {
        border-top-width: 2px;
    }
</style>
@endsection

@section('content')


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
                <div class="card-title font-weight-bold h5 text-center  row">

                    <div class="card-text">
                        <div class="row">
                            {{-- approval  Status --}}
                            <div class="col-md-4 col-sm-12">
                                <div class="h6">
                                    <span class="font-weight-bold">@lang('main.approval_status'): </span>

                                    @if ($order->isApproved())
                                    @lang('main.approved')
                                    @else
                                    @lang('main.not_approved')
                                    @endif

                                </div>
                                <hr>
                            </div>
 
                            {{--  shipping  Status --}}
                            <div class="col-md-4 col-sm-12">
                                <div class="h6">
                                    <span class="font-weight-bold">@lang('main.shipping_status'): </span>


                                    @if ($order->isShipped() == 0)

                                    @lang('main.order_not_in_shipping')

                                    @else
                                    @lang('main.order_in_shipping')
                                    @endif

                                </div>
                                <hr>
                            </div>

                            {{-- delivery  Status --}}
                            <div class="col-md-4 col-sm-12">
                                <div class="h6">
                                    <span class="font-weight-bold">@lang('main.delivery_status'): </span>


                                    @if ($order->isDelivered() == 0)

                                    @lang('main.not_delivered')
                                    @else
                                    @lang('main.delivered')
                                    @endif

                                </div>
                                <hr>
                            </div>

                            {{-- cancelation Status --}}
                            <div class="col-md-4 col-sm-12">
                                <div class="h6">
                                    <span class="font-weight-bold">@lang('main.cancelation_status'): </span>

                                    @if ($order->isCancelled() == 0)

                                    @lang('main.not_cancelled')

                                    @else
                                    @lang('main.canceled')
                                    @endif

                                    {{-- end order status --}}

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

            <!-- Page Heading -->
            <div class="col-xl-12 col-md-12 mb-3 mt-4">
                <h1 class="h4 font-weight-bold mb-0 text-gray-800">@lang('main.order')</h1>
            </div>
            {{-- Total Price Info --}}
            @include('dashboard.orders.includes._order_products_info')

        

        @endsection

        @section('scripts')
        <script>
            window.addEventListener('load', function () {
         window.onafterprint = window.close;
         window.print();
               
        });
        </script>  
        @endsection