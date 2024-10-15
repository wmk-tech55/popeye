<a href="#" data-toggle="modal" data-target="#restoreModel-{{ $id }}" class="btn btn-success btn-sm" title="@lang('main.restore')">
    <i class="fas fa-undo"></i>
</a>

@component('dashboard.components.restoreModelForm') 
    @slot('id',  $id ) 
    @slot('restoreTitle', trans('main.restore') . ' ' . $invoice_id) 
    @slot('url', route('dashboard.orders.restore', $id) ) 
@endcomponent
