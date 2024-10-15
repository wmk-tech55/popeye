<div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="categories_id">@lang('main.sub_categories')<span class="required"></span> </label>
    <div class="col-sm-10">
        <select name="categories_id[]" id="categories_id" class="form-control select2 @error('categories_id') is-invalid @enderror" data-placeholder="@lang('main.category')" required>
            <option value="" disabled selected>@lang('main.category')</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ old('categories_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name_by_lang }} 
                </option>
            @endforeach
        </select>

        @error('categories_id')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>




 