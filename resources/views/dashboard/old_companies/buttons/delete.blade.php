<a href="#" data-toggle="modal" data-target="#deleteModel-{{ $id }}" class="btn btn-danger btn-sm" title="@lang('main.delete')">
    <i class="fas fa-trash"></i>
</a>

@component('dashboard.components.deleteModelForm') 
    @slot('id',  $id ) 
    @slot('deleteTitle', trans('main.company') . ' ' . $full_name) 
    @slot('url', route('dashboard.companies.destroy', $id) ) 
@endcomponent
