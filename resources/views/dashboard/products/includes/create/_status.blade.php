{{-- Status --}}
<div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="status">@lang('main.status')<span class="required"></span> </label>
    <div class="col-sm-10">
        <input type="hidden" name="status" value="publish">  

        <input type="checkbox" id="status" name="status" class="switch" data-on-color="danger"
            data-off-color="success" data-on-text="@lang('main.pending')" data-off-text="@lang('main.publish')" value="pending">

        @error('status')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror 
    </div>
</div>
