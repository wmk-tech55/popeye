<div class="row">
    <div class="col-xl-3 col-md-3 col-sm-12 mb-3">
        <div class="card  border-left-danger">
    
            <div class="card-title font-weight-bold h5 text-center text-danger my-2">@lang('main.product')</div>
                
            <ul class="list-group list-group-flush">
                @forelse ($discount->products as $product)
                    
                    @admin  
                        <a href="{{ $product->path() }}" class="list-group-item list-group-item-action" target="_blank" rel="noopener noreferrer">{{ $product->name_by_lang }}</a>
                    @else
                        <li class="list-group-item">{{ $product->name_by_lang }}</li>
                    @endadmin

                @empty
                    <li class="list-group-item text-center font-weight-bold">@lang('main.not_found')</li>
                @endforelse
            </ul>
            
        </div>
    </div>
     
</div>