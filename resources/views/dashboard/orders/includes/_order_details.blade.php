<div class="col-xl-12 col-md-12 col-sm-12 mb-3">
    <div class="card border-left-warning">

        <div class="card-body">
            <div class="card-title font-weight-bold h5 text-center   ">

                <div class="card-text">
                    <div class="row">

                        {{-- invoive id --}}

                        <div class="col-md-4 col-sm-12">
                            <div class="h6">
                                <span class="font-weight-bold">@lang('main.invoice_id'): </span>
                                {{ $order->invoice_id }}
                            </div>
                            <hr>
                        </div>

                        @if ($order->date)
                            {{--  date --}}
                            <div class="col-md-4 col-sm-12">
                                <div class="h6">
                                    <span class="font-weight-bold">@lang('main.date'): </span>

                                             {{$order->date}}
                                </div>
                                <hr>
                            </div>
                        @endif
                        @if ($order->time)
                            {{--  Time --}}
                            <div class="col-md-4 col-sm-12">
                                <div class="h6">
                                    <span class="font-weight-bold">@lang('main.time'): </span>

                         {{Carbon\Carbon::parse($order->time)->format('H:i') }}
                                </div>
                                <hr>
                            </div>
                        @endif



                        @if ($order->payment_method)
                            {{-- payment method Status --}}
                            <div class="col-md-6 col-sm-12">
                                <div class="h6">
                                    <span class="font-weight-bold">@lang('main.payment_method'): </span>

                                    @lang('main.' . $order->payment_method)
                                </div>
                                <hr>
                            </div>
                        @endif



                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
