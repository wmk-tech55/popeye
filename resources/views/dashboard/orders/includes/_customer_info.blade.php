<div class="col-xl-12 col-md-12 col-sm-12 mb-3">
    <div class="card border-left-success">

        <div class="card-body">
            <div class="card-title font-weight-bold h5 text-center  row">
                <div class="card-title col-md-6 col-sm-12 mb-0">@lang('main.created_at'):
                    {{ $order->created_at ? $order->created_at->calendar() : __('main.date_not_available') }} </div>
                <div class="card-title col-md-6 col-sm-12 mb-0">@lang('main.updated_at'):
                    {{  $order->updated_at ? $order->updated_at->calendar() : __('main.date_not_available') }} </div>
            </div>
            <hr>
            <div class="card-text">
                <div class="row">
                    {{-- Customer --}}
                    <div class="col-md-4 col-sm-12">
                        <div class="h6">
                            <span class="font-weight-bold">@lang('main.customer'): </span>
                            {{ $order->full_name ?? '' }}
                        </div>
                        <hr>
                    </div>

                    {{-- Email --}}
                    <div class="col-md-4 col-sm-12">
                        <div class="h6">
                            <span class="font-weight-bold">@lang('main.email'): </span>

                            <a href="mailto:{{ $order->email }}">{{ $order->email }}</a>

                        </div>
                        <hr>
                    </div>

                    {{-- Phone --}}
                    <div class="col-md-4 col-sm-12">
                        <div class="h6">
                            <span class="font-weight-bold">@lang('main.phone'): </span>

                            <a href="tel:{{ $order->phone }}">{{ $order->phone }}</a>

                        </div>
                        <hr>
                    </div>

                    <div class="col-md-4 col-sm-12">
                        <div class="h6">
                            <span class="font-weight-bold">@lang('main.address'): </span>
                            {{$order->address}}
                        </div>
                        <hr>
                    </div>

                    @if ($order->city)
                        
                    <div class="col-md-4 col-sm-12">
                        <div class="h6">
                            <span class="font-weight-bold">@lang('main.city'): </span>
                            {{$order->city}}
                        </div>
                        <hr>
                    </div>
                    @endif
                    @if ($order->postal_code)
                    <div class="col-md-4 col-sm-12">
                        <div class="h6">
                            <span class="font-weight-bold">@lang('main.postal_code'): </span>
                            {{$order->postal_code}}
                        </div>
                        <hr>
                    </div>
                    @endif

                    @if ( $order->block_number)
                    <div class="col-md-4 col-sm-12">
                        <div class="h6">
                            <span class="font-weight-bold">@lang('main.block_number'): </span>
                            {{ $order->block_number}}
                        </div>
                        <hr>
                    </div>

                    @endif


                    @if ( $order->floor_number)
                    <div class="col-md-4 col-sm-12">
                        <div class="h6">
                            <span class="font-weight-bold">@lang('main.floor_number'): </span>
                            {{$order->floor_number}}
                        </div>
                        <hr>
                    </div>
                    @endif

                    @if ( $order->flat_number)
                    <div class="col-md-4 col-sm-12">
                        <div class="h6">
                            <span class="font-weight-bold">@lang('main.flat_number'): </span>
                            {{$order->flat_number}}
                        </div>
                        <hr>
                    </div>
                    @endif 
  
                </div>
            </div>
        </div>
    </div>
</div>