@php
    
    if (isset($_GET['categorizations']) && count($_GET['categorizations']) > 0 && is_array($_GET['categorizations'])) {
        $categorizations_id = $_GET['categorizations'];
    } else {
        $categorizations_id = [];
    }
    
@endphp


<form class="form-group mb-4 row m-2" method="GET" action="{{ route('dashboard.products.index') }}">

    <div class="col-sm-3 m-1">
        <select name="categorizations[]" class="select2 form-control @error('categorizations') is-invalid @enderror"
            data-placeholder="@lang('main.categorization')" autocomplete="off" multiple>

            @foreach ($listCategorizations as $categorization)
                <option value="{{ $categorization->id }}"
                    {{ in_array($categorization->id, $categorizations_id) ? 'selected' : '' }}>
                    {{ $categorization->name_by_lang }} </option>
            @endforeach


        </select>

    </div>


    <div class="col-sm-3 m-1  ">
        <button type="submit" class="btn btn-primary ml-1 mr-1">@lang('main.search')</button>
        @if (count($categorizations_id) > 0)
            <a href="{{ route('dashboard.products.index') }}" class="btn btn-info ml-1 mr-1"
                id="filter_clear">@lang('main.clear_filter')
            </a>
        @endif
    </div>
</form>


<hr>
