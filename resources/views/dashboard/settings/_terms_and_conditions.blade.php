{{-- En Terms and Conditions --}}
<div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="en_terms_and_conditions">@lang('main.en_terms_and_conditions')<span class="required"></span> </label>
    <div class="col-sm-10">
        <textarea name="en_terms_and_conditions" id="en_terms_and_conditions" class="form-control @error('en_terms_and_conditions') is-invalid @enderror"
            placeholder="@lang('main.en_terms_and_conditions')" required>
            {{ $settings->en_terms_and_conditions }}
        </textarea>
        @error('en_terms_and_conditions')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>

{{-- Ar Terms and Conditions --}}
<div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="ar_terms_and_conditions">@lang('main.ar_terms_and_conditions')<span class="required"></span> </label>
    <div class="col-sm-10">
        <textarea name="ar_terms_and_conditions" id="ar_terms_and_conditions" class="form-control @error('ar_terms_and_conditions') is-invalid @enderror"
            placeholder="@lang('main.ar_terms_and_conditions')" required>
            {{ $settings->ar_terms_and_conditions }}
        </textarea>
        @error('ar_terms_and_conditions')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>

{{-- En Privacy --}}
<div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="en_privacy_policy">@lang('main.en_privacy_policy')<span class="required"></span> </label>
    <div class="col-sm-10">
        <textarea name="en_privacy_policy" id="en_privacy_policy" class="form-control @error('en_privacy_policy') is-invalid @enderror"
            placeholder="@lang('main.en_privacy_policy')" required>
            {{ $settings->en_privacy_policy }}
        </textarea>
        @error('en_privacy_policy')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>

{{-- Ar Privacy --}}
<div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="ar_privacy_policy">@lang('main.ar_privacy_policy')<span class="required"></span> </label>
    <div class="col-sm-10">
        <textarea name="ar_privacy_policy" id="ar_privacy_policy" class="form-control @error('ar_privacy_policy') is-invalid @enderror"
            placeholder="@lang('main.ar_privacy_policy')" required>
            {{ $settings->ar_privacy_policy }}
        </textarea>
        @error('ar_privacy_policy')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>