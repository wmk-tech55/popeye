@if (auth()->user()->isAdmin())
<a href="#" data-toggle="modal" data-target="#deleteModel-{{ $id }}"
    class="btn btn-danger btn-sm {{auth()->user()->isAdmin() ? '' : 'disabled' }}" title="@lang('main.delete')">
    <i class="fas fa-trash"></i>
</a>
@component('dashboard.components.deleteModelForm')
@slot('id', $id )
@slot('deleteTitle', trans('main.order') . ' ' . $invoice_id)
@slot('url', route('dashboard.orders.destroy', $id) )
@endcomponent

@else
<a href="#" data-toggle="modal" data-target="" class="btn btn-danger btn-sm  disabled " title="@lang('main.delete')">
    <i class="fas fa-trash"></i>
</a>
@endif