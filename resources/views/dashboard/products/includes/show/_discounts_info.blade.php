{{-- discounts --}}
<div class="col-md-6 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang('main.discount'): </span>

        <a href="{{ $product->discount->path() }}" class="" target="_blank"
            rel="noopener noreferrer">{{ $product->discount->name_by_lang }} ( {{ $product->discount->discount }} %)
        </a>
    </div>
    <hr>
</div>