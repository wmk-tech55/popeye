     {{-- EN Name --}}
     <div class="form-group my-4 row">
        <label class="col-sm-2 col-form-label" for="en_name">@lang('main.en_name')<span
                class="required"></span> </label>
        <div class="col-sm-10">
            <input type="text" name="en_name" value="{{ $country->en_name }}" id="en_name"
                class="form-control @error('en_name') is-invalid @enderror"
                placeholder="@lang('main.en_name')" required>

            @error('en_name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>

    {{-- AR Name --}}
    <div class="form-group mb-4 row">
        <label class="col-sm-2 col-form-label" for="ar_name">@lang('main.ar_name')<span
                class="required"></span> </label>
        <div class="col-sm-10">
            <input type="text" name="ar_name" value="{{ $country->ar_name }}" id="ar_name"
                class="form-control @error('ar_name') is-invalid @enderror"
                placeholder="@lang('main.ar_name')" required>

            @error('ar_name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>

{{--  en_currency --}}
<div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="en_currency">@lang('main.en_currency')<span class="required"></span> </label>
    <div class="col-sm-10">
        <input type="text" name="en_currency" value="{{ $country->en_currency }}" id="en_currency"
            class="form-control @error('en_currency') is-invalid @enderror" placeholder="@lang('main.en_currency')" required>
        @error('en_currency')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
{{--  ar_currency --}}
<div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="ar_currency">@lang('main.ar_currency')<span class="required"></span> </label>
    <div class="col-sm-10">
        <input type="text" name="ar_currency" value="{{ $country->ar_currency }}" id="ar_currency"
            class="form-control @error('ar_currency') is-invalid @enderror" placeholder="@lang('main.ar_currency')" required>
        @error('ar_currency')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
    {{--  country_code --}}
    <div class="form-group mb-4 row">
        <label class="col-sm-2 col-form-label" for="country_code">@lang('main.country_code')<span
                class="required"></span> </label>
        <div class="col-sm-10">
            <input type="text" name="country_code" value="{{ $country->country_code }}"
                id="country_code" class="form-control @error('country_code') is-invalid @enderror"
                placeholder="@lang('main.country_code_iso_example')" required>
            @error('country_code')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>

    