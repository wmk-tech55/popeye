@admin
    {{-- Owner --}}
    <div class="col-md-12 col-sm-12">
        <div class="h6">
            <span class="font-weight-bold">@lang('main.creator'): </span>

            @if (!! $discount->creator)
                <a href="{{ $discount->creator->path() }}" target="_blank" rel="noopener noreferrer">{{ $discount->creator->full_name }}</a>
            @else 
                <span class="text-muted">@lang('main.not_found')</span>
            @endif
        </div>
        <hr>
    </div>
@endadmin

{{-- En Name --}}
<div class="col-md-6 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang('main.en_name'): </span>

        <span class="text-muted">{{ $discount->en_name }}</span>
    </div>
    <hr>
</div>

{{-- Ar Name --}}
<div class="col-md-6 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang('main.ar_name'): </span>

        <span class="text-muted">{{ $discount->ar_name }}</span>
    </div>
    <hr>
</div>

{{-- size --}}
<div class="col-md-6 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang('main.discount'): </span>

        <span class="text-muted">{{ $discount->discount }} %</span>
    </div>
    <hr>
</div>
 