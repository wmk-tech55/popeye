@admin
    {{-- Owner --}}
    <div class="col-md-12 col-sm-12">
        <div class="h6">
            <span class="font-weight-bold">@lang('main.creator'): </span>

            @if (!!$product->creator)
                <a href="{{ $product->creator->path() }}" target="_blank"
                    rel="noopener noreferrer">{{ $product->creator->full_name }}</a>
            @else
                <span class="text-muted">@lang('main.not_found')</span>
            @endif
        </div>
        <hr>
    </div>
@endadmin



{{-- Ar Name --}}
<div class="col-md-6 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang('main.name'): </span>

        <span class="text-muted">{{ $product->ar_name }}</span>
    </div>
    <hr>
</div>

{{-- price --}}
<div class="col-md-6 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang('main.price'): </span>

        <span class="text-muted">{{ $product->price . currency() }}</span>
    </div>
    <hr>
</div>

@if ($product->has_discount)
    {{-- price_after_discount --}}
    <div class="col-md-6 col-sm-12">
        <div class="h6">
            <span class="font-weight-bold">@lang('main.price_after_discount'): </span>

            <span class="text-muted">{{ $product->price_after_discount . currency() }}</span>
        </div>
        <hr>
    </div>
    {{-- discount percentage --}}
    <div class="col-md-6 col-sm-12">
        <div class="h6">
            <span class="font-weight-bold">@lang('main.discount_percentage'): </span>

            <span class="text-muted">{{ $product->discount_percentage . ' %' }}</span>
        </div>
        <hr>
    </div>
@endif



{{-- categorization --}}
<div class="col-md-6 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang('main.store_categorizations'): </span>

        <span class="text-muted">
            <a href="{{ $product->categorization ? $product->categorization->path() : '#' }}" target="_blank"
                rel="noopener noreferrer">
                {{ $product->categorization ? $product->categorization->name_by_lang : trans('main.not_found') }}
            </a>

        </span>
    </div>
    <hr>
</div>

{{-- categories --}}
<div class="col-md-6 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang('main.category'): </span>

        <span class="text-muted">
            <a href="{{ $product->category ? $product->category->path() : '#' }}" target="_blank"
                rel="noopener noreferrer">
                {{ $product->category ? $product->category->name_by_lang : trans('main.not_found') }}
            </a>

        </span>
    </div>
    <hr>
</div>



{{-- Status --}}
<div class="col-md-12 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang('main.status'): </span>

        @if ($product->isPublished())
            <span class="badge badge-success">@lang('main.published')</span>
        @else
            <span class="badge badge-warning">@lang('main.pending')</span>
        @endif
    </div>
    <hr>
</div>
