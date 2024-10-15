{{-- Price Ratio --}}
<div class="form-group my-4 row">
    <label class="col-sm-2 col-form-label" for="usd_price_ratio">@lang('main.usd_price_ratio')<span class="required"></span> </label>
    <div class="col-sm-10">
        <input type="text" name="usd_price_ratio" value="{{ $settings->usd_price_ratio }}" id="usd_price_ratio" class="form-control @error('usd_price_ratio') is-invalid @enderror" placeholder="@lang('main.usd_price_ratio')" required>

        @error('usd_price_ratio')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
