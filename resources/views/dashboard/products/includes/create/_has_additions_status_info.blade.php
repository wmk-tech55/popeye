<div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="additions">@lang('main.additions') </label>
    <div class="col-sm-10">

        <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" id="yes" name="has_additions" value="yes"
                {{ old('has_additions', 'no') === 'yes' ? 'checked' : '' }} class="custom-control-input">
            <label class="custom-control-label" for="yes">@lang('main.yes')</label>
        </div>

        <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" id="no" name="has_additions" value="no"
                {{ old('has_additions', 'no') === 'no' ? 'checked' : '' }} class="custom-control-input">
            <label class="custom-control-label" for="no">@lang('main.no')</label>
        </div>
        @error('type')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

    </div>
</div>