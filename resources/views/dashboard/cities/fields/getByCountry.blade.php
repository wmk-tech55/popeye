<div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="city_id">@lang('main.city')<span class="required"></span> </label>
    <div class="col-sm-10">
        <select name="city_id" id="city_id" class="form-control select2 @error('city_id') is-invalid @enderror" data-placeholder="@lang('main.city')" required>
            <option value="" disabled selected>@lang('main.city')</option>
            @foreach ($cities as $city)
                <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>
                    {{ $city->name_by_lang }} 
                </option>
            @endforeach
        </select>

        @error('city_id')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>