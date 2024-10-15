 
{{-- en_warning_message --}}
<div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="en_warning_message">@lang('main.en_warning_message')<span class="required"></span> </label>
    <div class="col-sm-10">
        <input type="text" name="en_warning_message" value="{{ $settings->en_warning_message }}" id="en_warning_message" class="form-control @error('en_warning_message') is-invalid @enderror" placeholder="@lang('main.en_warning_message')" required>
        @error('en_warning_message')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
{{-- ar_warning_message --}}
<div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="ar_warning_message">@lang('main.ar_warning_message')<span class="required"></span> </label>
    <div class="col-sm-10">
        <input type="text" name="ar_warning_message" value="{{ $settings->ar_warning_message }}" id="ar_warning_message" class="form-control @error('ar_warning_message') is-invalid @enderror" placeholder="@lang('main.ar_warning_message')" required>
        @error('ar_warning_message')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>

 