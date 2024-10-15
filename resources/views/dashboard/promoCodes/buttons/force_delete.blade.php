<a href="#" data-toggle="modal" data-target="#deleteModel-{{ $id }}" class="btn btn-danger btn-sm" title="@lang('main.delete')">
    <i class="fas fa-times"></i>
</a>

@component('dashboard.components.deleteModelForm') 
    @slot('id',  $id ) 
    @slot('deleteTitle', trans('main.promoCode') . ' ' . $code) 
    @slot('url', route('dashboard.promoCodes.force_delete', $id) ) 
@endcomponent
