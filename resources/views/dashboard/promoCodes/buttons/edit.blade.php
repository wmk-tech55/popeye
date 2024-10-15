@if(auth()->id() === $creator_id)
    <a href="{{ route('dashboard.promoCodes.edit', $id) }}" class="btn btn-info btn-sm" title="@lang('main.edit')">
        <i class="fas fa-edit"></i>    
    </a>
@else
    <button type="button" class="btn btn-secondary btn-sm disabled" title="@lang('main.not_owner')" disabled>
        <i class="fas fa-edit"></i>
    </button>
@endcan
