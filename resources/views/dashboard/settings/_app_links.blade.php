{{-- android --}}
<div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="android">@lang('main.android') </label>
    <div class="col-sm-10">
        <input type="text" name="android" value="{{ $settings->android }}" id="android" class="form-control @error('android') is-invalid @enderror" placeholder="@lang('main.android')">
        @error('android')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>

{{-- ios --}}
<div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="ios">@lang('main.ios') </label>
    <div class="col-sm-10">
        <input type="text" name="ios" value="{{ $settings->ios }}" id="ios" class="form-control @error('ios') is-invalid @enderror" placeholder="@lang('main.ios')">
        @error('ios')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
 