@php

$category_id = '';
$parent_id = '';

foreach ($discount->products as $parenCategories) {
foreach ($parenCategories->categories as $category ) {

$category_id = $category->id ;

$parent_id = $category->parent->id;

}
}
@endphp

{{-- Categories --}}
<div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="category_parent_id">@lang('main.categories')<span
            class="required"></span> </label>
    <div class="col-sm-10">
        <select name="category_parent_id" id="category_parent_id"
            class="form-control select2 @error('category_parent_id') is-invalid @enderror"
            data-placeholder="@lang('main.categories')" multiple required>

            @foreach ($categories as $category)
            <option value="{{ $category->id }}" {{ $category->id == $parent_id ? 'selected' : '' }}>
                {{ $category->name_by_lang }}
            </option>
            @endforeach
        </select>

        @error('category_parent_id')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>

{{-- sub Categories --}}
 
<div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="categories_id">@lang('main.sub_categories')<span
            class="required"></span>
    </label>
    <div class="col-sm-10">
        <select name="categories_id[]" id="categories_id"
            class="form-control category_parent_container  select2 @error('categories_id') is-invalid @enderror"
            data-placeholder="@lang('main.sub_categories')"   multiple>

            @foreach ($subCategories as $subCategory)

              <option value="{{ $subCategory->id }}" {{  $subCategory->id  == $category_id? 'selected' : '' }}>
                  {{ $subCategory->name_by_lang }}
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