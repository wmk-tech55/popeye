
@admin
    {{-- Owner --}}
    <div class="col-md-12 col-sm-12">
        <div class="h6">
            <span class="font-weight-bold">@lang('main.creator'): </span>

            @if (!! $banner->creator)
                <a href="{{ $banner->creator->path() }}" target="_blank" rel="noopener noreferrer">{{ $banner->creator->full_name }}</a>
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
        <span class="font-weight-bold">@lang('main.en_title'): </span>

        <span class="text-muted">{{ $banner->en_name }}</span>
    </div>
    <hr>
</div>

{{-- Ar Name --}}
<div class="col-md-6 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang('main.ar_title'): </span>

        <span class="text-muted">{{ $banner->ar_name }}</span>
    </div>
    <hr>
</div>
 
{{-- Store --}}
<div class="col-md-12 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang('main.company'): </span>

        @if (!! $banner->store)
            <a href="{{ $banner->store->path() }}" target="_blank" rel="noopener noreferrer">{{ $banner->store->full_name }}</a>
        @else 
            <span class="text-muted">@lang('main.not_found')</span>
        @endif
    </div>
    <hr>
</div>