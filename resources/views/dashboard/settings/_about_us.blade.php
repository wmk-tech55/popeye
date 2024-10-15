{{-- En About Us --}}
<div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="en_about_us">@lang('main.en_about_us')<span class="required"></span> </label>
    <div class="col-sm-10">
        <textarea name="en_about_us" id="en_about_us" class="form-control @error('en_about_us') is-invalid @enderror"
            placeholder="@lang('main.en_about_us')" required>
            {{ $settings->en_about_us }}
        </textarea>
        @error('en_about_us')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>

{{-- Ar About Us --}}
<div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="ar_about_us">@lang('main.ar_about_us')<span class="required"></span> </label>
    <div class="col-sm-10">
        <textarea name="ar_about_us" id="ar_about_us" class="form-control @error('ar_about_us') is-invalid @enderror"
            placeholder="@lang('main.ar_about_us')" required>
            {{ $settings->ar_about_us }}
        </textarea>
        @error('ar_about_us')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>

 