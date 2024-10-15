<a href="#" data-toggle="modal" data-target="#deleteModel-{{ $id }}" class="btn btn-danger btn-sm" title="@lang('main.delete')">
    <i class="fas fa-trash"></i>
</a>

@component('dashboard.components.deleteModelForm') 
    @slot('id',  $id) 
    @slot('deleteTitle', trans('main.faq') . ' ' . $question_by_lang) 
    @slot('url', route('dashboard.faqs.destroy', $id) ) 
@endcomponent
