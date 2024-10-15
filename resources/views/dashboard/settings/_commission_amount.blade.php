{{-- commission amount --}}
<div class="form-group my-4 row">
    <label class="col-sm-4 col-form-label" for="commission_percentage">
        @lang('main.commission_percentage') %<span class="required"></span> </label>
    <div class="col-sm-6">
        <small class="badge badge-warning mt-2 mb-1 p-2"> @lang('main.must_be_percentage') %</small>
        <input type="text" name="commission_percentage" value="{{ $settings->commission_percentage }}" id="commission_percentage"
            class="form-control @error('commission_percentage') is-invalid @enderror"
            placeholder="@lang('main.commission_percentage') @lang('main.must_be_percentage')%" required>

        @error('commission_percentage')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>