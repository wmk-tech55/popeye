<a href="#" data-toggle="modal" data-target="#deleteModel-{{ $id }}" class="btn btn-danger btn-sm" title="@lang('main.delete')">
    <i class="fas fa-times"></i>
</a>

@component('dashboard.components.deleteModelForm') 
    @slot('id',  $id ) 
    @slot('deleteTitle', trans('main.country') . ' ' . $name_by_lang) 
    @slot('url', route('dashboard.categorizations.force_delete', $id) ) 
@endcomponent
