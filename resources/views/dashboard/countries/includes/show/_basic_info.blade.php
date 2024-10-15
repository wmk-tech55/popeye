  {{-- EN Name --}}
  <div class="col-md-6 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang('main.en_name'): </span>
        <span class="text-muted">{{ $country->en_name }}</span>
    </div>
    <hr>
</div>

{{-- AR Name --}}
<div class="col-md-6 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang('main.ar_name'): </span>
        <span class="text-muted">{{ $country->ar_name }}</span>
    </div>
    <hr>
</div>
{{-- EN curreny --}}
<div class="col-md-6 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang('main.en_currency'): </span>
        <span class="text-muted">{{ $country->en_currency }}</span>
    </div>
    <hr>
</div>

{{-- AR curreny --}}
<div class="col-md-6 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang('main.ar_currency'): </span>
        <span class="text-muted">{{ $country->ar_currency }}</span>
    </div>
    <hr>
</div>

{{-- country Code --}}
<div class="col-md-6 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang('main.country_code'): </span>
         
        <span class="text-muted">{{ $country->country_code }}</span>
    </div>
    <hr>
</div>


{{-- Status --}}
<div class="col-md-6 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang('main.status'): </span>

        @if ($country->isActive())
            <span class="badge badge-success">@lang('main.active')</span>
        @else
            <span class="badge badge-danger">@lang('main.disabled')</span>
        @endif
    </div>
    <hr>
</div>