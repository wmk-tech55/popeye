@if ($subCategories->count() > 0)
    {{-- categories --}}
    <div class="form-group mb-4 row">
        <label class="col-sm-2 col-form-label" for="categories_id">@lang('main.categories')<span class="required"></span></label>
        <div class="col-sm-7">
            <select name="categories_id[]" class="form-control select2 categories_id @error('categories_id') is-invalid @enderror" data-placeholder="@lang('main.categories')" multiple required>
                @foreach ($subCategories as $category)
                    <option class="option-parent" {{ in_array($category->id, old('categories_id', [])) ? 'selected' : '' }}  value="{{ $category->id }}">
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

    <div class="subcategories_container"></div>
@endif