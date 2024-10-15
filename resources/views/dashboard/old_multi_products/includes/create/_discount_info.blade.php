{{-- discounts --}}
<div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="discount_id">@lang('main.discounts') </label>
    <div class="col-sm-10">
        <select name="discount_id" id="discount_id" class="form-control select2 @error('discount_id') is-invalid @enderror" data-placeholder="@lang('main.discounts')"    >
         <option disabled>@lang('main.discounts')</option>
            @foreach ($discounts as $discount)
                <option value="{{ $discount->id }}" {{  $discount->id == old('discount_id')   ? 'selected' : '' }}>
                    {{ $discount->name_by_lang }} 
                </option>
            @endforeach
        </select>

        @error('discount_id')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>

 
 
 