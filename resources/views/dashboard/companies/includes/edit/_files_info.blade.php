

{{-- Logo --}}
<div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="logo">@lang('main.logo')</label>
    <div class="col-sm-10">

        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            @lang('main.jpg_png_images_only')
        </div>


        <div class="fileinput fileinput-new" data-provides="fileinput">
            <div class="fileinput-preview thumbnail" data-trigger="fileinput">
                <img src="{{ $company->mainMediaUrl('logo') }}" alt="{{ $company->companyname }}" />
            </div>
            <div>
                <span class="btn blue btn-outline btn-file">
                    <span class="fileinput-new btn btn-success"> @lang('main.select_image') </span>
                    <span class="fileinput-exists btn btn-info"> @lang('main.change') </span>
                    <input type="file" name="logo" id="logo">
                </span>
                <a href="javascript:;" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">
                    @lang('main.remove') </a>
            </div>
        </div>

        @error('logo')
            <div class="invalid-feedback d-block">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>

{{-- commercial_license --}}
<div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="commercial_license">@lang('main.commercial_license')</label>
    <div class="col-sm-10">

        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            @lang('main.pdf_jpg_png_images_only')
        </div>


        <div class="fileinput fileinput-new" data-provides="fileinput">
            <div class="fileinput-preview thumbnail" data-trigger="fileinput">


                @if (pathinfo($company->allUserMedia['commercial_license'], PATHINFO_EXTENSION) == 'pdf')
                    <embed src="{{ $company->allUserMedia['commercial_license'] }}" width="300" height="300">
                @else
                    <img src="{{ $company->allUserMedia['commercial_license'] }}" alt="{{ $company->full_name }}" />
                @endif


            </div>
            <div>
                <span class="btn blue btn-outline btn-file">
                    <span class="fileinput-new btn btn-success"> @lang('main.select_file') </span>
                    <span class="fileinput-exists btn btn-info"> @lang('main.change') </span>
                    <input type="file" name="commercial_license" id="commercial_license">
                </span>
                <a href="javascript:;" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">
                    @lang('main.remove') </a>
            </div>
        </div>

        @error('commercial_license')
            <div class="invalid-feedback d-block">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
