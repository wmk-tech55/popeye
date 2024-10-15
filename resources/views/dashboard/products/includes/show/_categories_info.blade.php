<div class="col-xl-3 col-md-3 col-sm-12 mb-3">
    <div class="card  border-left-danger">

        <div class="card-title font-weight-bold h5 text-center text-danger my-2">@lang('main.categories')</div>

        <ul class="list-group list-group-flush">
            @forelse ($product->categories as $category)

            @admin
            <a href="{{ $category->path() }}" class="list-group-item list-group-item-action" target="_blank"
                rel="noopener noreferrer">{{ $category->name_by_lang }}</a>
            @else
            <li class="list-group-item">{{ $category->name_by_lang }}</li>
            @endadmin

            @empty
            <li class="list-group-item text-center font-weight-bold">@lang('main.not_found')</li>
            @endforelse
        </ul>

    </div>
</div>