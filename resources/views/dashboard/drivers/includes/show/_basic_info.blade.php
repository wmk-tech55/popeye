{{-- Username --}}
{{-- <div class="col-md-6 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang('main.username'): </span>

        <span class="text-muted">{{ $driver->username }}</span>

    </div>
    <hr>
</div> --}}

{{-- Full Name --}}
<div class="col-md-6 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang('main.full_name'): </span>

        <span class="text-muted">{{ $driver->full_name }}</span>
    </div>
    <hr>
</div>

{{-- Email --}}
<div class="col-md-6 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang('main.email'): </span>

        <span class="text-muted">{{ $driver->email }}</span>

    </div>
    <hr>
</div>

{{-- Phone --}}
<div class="col-md-6 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang('main.phone'): </span>

        <span class="text-muted">{{ $driver->phone }}</span>

    </div>
    <hr>
</div>



{{-- country --}}
@if ($driver->userCountry)
    <div class="col-md-6 col-sm-12">
        <div class="h6">
            <span class="font-weight-bold">@lang('main.country'): </span>

            <span class="text-muted">{{ $driver->userCountry->name_by_lang ?? trans('main.not_found') }}</span>

        </div>
        <hr>
    </div>
@endif


{{-- city --}}
@if ($driver->city)
    <div class="col-md-6 col-sm-12">
        <div class="h6">
            <span class="font-weight-bold">@lang('main.city'): </span>

            <span class="text-muted">{{ $driver->city ?? trans('main.not_found') }}</span>

        </div>
        <hr>
    </div>
@endif
{{-- area --}}
@if ($driver->area)
    <div class="col-md-6 col-sm-12">
        <div class="h6">
            <span class="font-weight-bold">@lang('main.area'): </span>

            <span class="text-muted">{{ $driver->area ?? trans('main.not_found') }}</span>

        </div>
        <hr>
    </div>
@endif

{{-- address --}}
@if ($driver->address)
    <div class="col-md-12 col-sm-12">
        <div class="h6">
            <span class="font-weight-bold">@lang('main.address'): </span>

            <span class="text-muted">{{ $driver->address ?? trans('main.not_found') }}</span>

        </div>
        <hr>
    </div>
@endif


{{-- Status --}}
<div class="col-md-6 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang('main.status'): </span>

        @if ($driver->isActive())
            <span class="badge badge-success">@lang('main.active')</span>
        @elseif($driver->isPending())
            <span class="badge badge-warning">@lang('main.pending')</span>
        @else
            <span class="badge badge-danger">@lang('main.disabled')</span>
        @endif
    </div>
    <hr>
</div>
