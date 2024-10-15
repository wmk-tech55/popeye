{{-- Bank Name --}}
<div class="col-md-6 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang('main.bank_name'): </span>

        <span class="text-muted">
            @if (!! $driver->bank_name)
                {{ $driver->bank_name }}
            @else
                @lang('main.not_found')
            @endif
        </span>
    </div>
    <hr>
</div>

{{-- Bank Account Number --}}
<div class="col-md-6 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang('main.bank_account_number'): </span>

        <span class="text-muted">

            @if (!! $driver->bank_account_number)
                {{ $driver->bank_account_number }}
            @else
                @lang('main.not_found')
            @endif
        
        </span>
    </div>
    <hr>
</div>

{{-- Map Url --}}
<div class="col-md-12 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang("main.map_url"): </span>
        @if (!! $driver->map_url)
            <a href="{{ $driver->map_url }}" class="btn btn-success btn-sm" target="_blank" rel="noopener noreferrer">
                <i class="fas fa-link"></i>
                @lang('main.visit_url')
            </a>
        @else
            <span class="text-muted">
                @lang('main.not_found')
            </span>
        @endif

    </div>
    <hr>
</div>