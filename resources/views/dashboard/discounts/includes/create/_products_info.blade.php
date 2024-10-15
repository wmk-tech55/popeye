{{-- sub products --}}
 
<div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="products_id">@lang('main.products')<span class="required"></span>
    </label>
    <div class="col-sm-10">
        <select name="products_id[]" id="products_id"
            class="form-control products_container  select2 @error('products_id') is-invalid @enderror"
            data-placeholder="@lang('main.products')"    multiple>

              @foreach ($products as $product)

            <option value="{{ $product->id }}" >
                {{ $product->name_by_lang }} 
            </option>
            @endforeach
        </select>

       </select>

        @error('products_id')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>