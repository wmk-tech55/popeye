{{-- Images --}}
<div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="images">@lang('main.images')</label>
    <div class="col-sm-10">
        <input type="file" name="images[]" id="images" accept=".png,.jpg,.jpeg" multiple>

        @error('images')
            <div class="invalid-feedback d-block">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
<div class="col-xl-12 col-md-12 col-sm-12">
    {{-- Media --}}
    @include('dashboard.products.includes.show._media')
</div>

 