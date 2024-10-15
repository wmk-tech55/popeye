{{-- Image --}}
<div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="image">@lang('main.image')<span class="required"></span> </label>
    <div class="col-sm-10">

        <div class="fileinput fileinput-new" data-provides="fileinput">
            <div class="fileinput-preview thumbnail" data-trigger="fileinput">

            </div>
            <div>
                <span class="btn blue btn-outline btn-file">
                    <span class="fileinput-new btn btn-success"> @lang('main.select_image') </span>
                    <span class="fileinput-exists btn btn-info"> @lang('main.change') </span>
                    <input type="file" name="image" id="image" accept=".jpg,.png,.jpeg" required>
                </span>
                <a href="javascript:;" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">
                    @lang('main.remove') </a>
            </div>
        </div>



        @error('image')
            <div class="invalid-feedback d-block">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
