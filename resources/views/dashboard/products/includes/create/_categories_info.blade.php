{{-- category --}}


<div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="category_id">@lang('main.category')<span class="required"></span> </label>
    <div class="col-sm-10">

       <select name="category_id" id="category_id"
    class="form-control select2 @error('category_id') is-invalid @enderror"
    data-placeholder="@lang('main.category')" required>
    <option value="" disabled selected>@lang('main.category')</option>
    {{-- @foreach ($categories as $category)
        <option value="{{ $category->id }}" {{ $category->id == old('category_id') ? 'selected' : '' }}>
            {{ $category->name_by_lang }}
        </option>
    @endforeach --}}
</select>

        @error('category_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>
</div>
