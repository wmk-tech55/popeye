{{-- country_id --}}
<div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="country_id">@lang('main.countries')<span class="required"></span> </label>
    <div class="col-sm-10">
        <select name="country_data_id" id="country_id"
            class="form-control select2 @error('country_id') is-invalid @enderror" data-placeholder="@lang('main.country')"
            required>
            <option value="" disabled selected>@lang('main.country')</option>
            @foreach ($countries as $country)
                <option value="{{ $country->id }}"
                    {{ old('country_id', auth()->user()->active_country_id) == $country->id ? 'selected' : '' }}>
                    {{ $country->name_by_lang }} </option>
            @endforeach
        </select>
        @error('country_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
