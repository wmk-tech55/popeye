@if(auth()->id() === $creator_id || auth()->user()->isAdmin())
    <a href="#" data-toggle="modal" data-target="#deleteModel-{{ $id }}" class="btn btn-danger btn-sm" title="@lang('main.delete')">
        <i class="fas fa-trash"></i>
    </a>

    @component('dashboard.components.deleteModelForm') 
        @slot('id',  $id ) 
        @slot('deleteTitle', trans('main.product') . ' ' . $name_by_lang) 
        @slot('url', route('dashboard.products.destroy', $id) ) 
    @endcomponent

@else
    <button type="button" class="btn btn-secondary btn-sm disabled" title="@lang('main.not_owner')" disabled>
        <i class="fas fa-trash"></i>
    </button>
@endcan