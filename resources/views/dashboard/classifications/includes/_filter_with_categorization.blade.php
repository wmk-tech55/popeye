@php
if(isset($_GET['categorization_id'])){
$categorization_id = $_GET['categorization_id'] ;
}else{
$categorization_id ='';
}


@endphp

<div class="form-group mb-4 row   ">

    <div class="col-sm-3 m-2">
        <select name="categorization_id" id="categorization_id"
            class="select2 form-control @error('categorization_id') is-invalid @enderror"
            placeholder="@lang('main.categorization')" autocomplete="off" required>
            <option value=" " disabled selected>@lang('main.categorization')</option>

            @foreach ($categorizations as $categorization)
            <option value="{{$categorization->id}}" {{ $categorization_id==$categorization->id ? 'selected' : '' }}>
                {{$categorization->name_by_lang}} </option>
            @endforeach


        </select>

    </div>

    @if ($categorization_id !='')

    <div class="col-sm-3 m-2  ">
        <button class="btn btn-info   ml-1 mr-1" id="filter_clear">@lang('main.clear_filter')</button>
    </div>
    @endif
</div>

<hr>