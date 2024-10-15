{{-- Map Url --}}
<div class="form-group my-4 row">
    <label class="col-sm-2 col-form-label" for="map_url">@lang('main.map_url')</label>
    <div class="col-sm-10">
        <input type="url" name="map_url" value="{{ old('map_url') }}" id="map_url" class="form-control @error('map_url') is-invalid @enderror" placeholder="@lang('main.map_url')">
        
        @error('map_url')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>

{{-- Bank Name --}}
<div class="form-group my-4 row">
    <label class="col-sm-2 col-form-label" for="bank_name">@lang('main.bank_name') </label>
    <div class="col-sm-10">
        <input type="text" name="bank_name" value="{{ old('bank_name') }}" id="bank_name" class="form-control @error('bank_name') is-invalid @enderror" placeholder="@lang('main.bank_name')">
        
        @error('bank_name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>

{{-- Bank Account Number --}}
<div class="form-group my-4 row">
    <label class="col-sm-2 col-form-label" for="bank_account_number">@lang('main.bank_account_number') </label>
    <div class="col-sm-10">
        <input type="text" name="bank_account_number" value="{{ old('bank_account_number') }}" id="bank_account_number" class="form-control @error('bank_account_number') is-invalid @enderror" placeholder="@lang('main.bank_account_number')">
        
        @error('bank_account_number')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
