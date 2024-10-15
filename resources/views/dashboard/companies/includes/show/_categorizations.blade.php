{{--  
    <div class="col-xl-12 col-md-12 col-sm-12 mb-3">
        <div class="card  border-left-warning">
    
            <div class="card-title font-weight-bold h5 text-center text-warning my-2">@lang('main.categorizations')</div>
                
            <ul class="list-group list-group-flush">
                @forelse ($company->categorizations as $categorization)
                    
                    @admin  
                        <a href="{{ $categorization->path() }}" class="list-group-item list-group-item-action" target="_blank" rel="noopener noreferrer">{{ $categorization->name_by_lang }}</a>
                    @else
                        <li class="list-group-item">{{ $categorization->name_by_lang }}</li>
                    @endadmin

                @empty
                    <li class="list-group-item text-center font-weight-bold">@lang('main.not_found')</li>
                @endforelse
            </ul>
            
        </div>
    </div>
  --}}