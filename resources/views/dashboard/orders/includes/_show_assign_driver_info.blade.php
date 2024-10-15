<div class="col-md-12 col-sm-12">
  <div class="h6">

    @if ( $order->driver)
    <span class="font-weight-bold">@lang('main.driver'): </span>
    <a href="{{  $order->driver->path() }}" class=" list-group-item-action" target="_blank" rel="noopener noreferrer">
      {{$order->driver->full_name}}</a>
    <hr>
    <span class="font-weight-bold">@lang('main.phone'): </span>
    <a href="tel:{{ $customer->phone }}">{{ $customer->phone }}</a>

    @else
    <span class="text-muted">@lang('main.no_driver_is_assigned')</span>
    @endif

  </div>
  <hr>
</div>