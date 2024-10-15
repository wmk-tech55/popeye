
@if (!empty($children))
<a href="{{ route('dashboard.categories.subCategories.index', $id) }}" class="btn btn-primary btn-sm" title="@lang('main.show_subCategories')">
    <i class="fa fa-list-alt"></i>
</a>
@else  
<a href="#" class="btn btn-primary btn-sm disabled" disabled title="@lang('main.disabledd')">
    <i class="fa fa-list-alt"></i>
</a>

@endif

