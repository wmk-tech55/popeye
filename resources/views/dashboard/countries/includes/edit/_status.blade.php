 {{-- Status --}}
 <div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="status">@lang('main.status')<span
            class="required"></span> </label>
    <div class="col-sm-10">
        <input type="hidden" name="status" value="disable">

        <input type="checkbox" id="status" name="status" class="switch"
            data-on-color="success" data-off-color="danger" data-on-text="@lang('main.active')"
            data-off-text="@lang('main.disabled')" value="active"
            {{ $country->status === 'active' ? 'checked' : '' }}>

        @error('status')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>