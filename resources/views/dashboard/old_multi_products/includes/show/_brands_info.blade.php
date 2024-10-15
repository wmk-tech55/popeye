<div class="col-xl-3 col-md-3 col-sm-12 mb-3">
    <div class="card  border-left-warning">

        <div class="card-title font-weight-bold h5 text-center text-warning my-2">@lang('main.brand')</div>

        <ul class="list-group list-group-flush">

            @admin
            <a href="{{ $product->brand->path() }}" class="list-group-item list-group-item-action" target="_blank"
                rel="noopener noreferrer">{{ $product->brand->name_by_lang }}</a>
            @else
            <li class="list-group-item">{{ $product->brand->name_by_lang }}</li>
            @endadmin

        </ul>
    </div>
</div>