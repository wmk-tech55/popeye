{{-- About us Image --}}
<div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="logo">@lang('main.logo')</label>
    <div class="col-sm-10">


        <div class="fileinput fileinput-new" data-provides="fileinput">
            <div class="fileinput-preview thumbnail" data-trigger="fileinput">
                <img src="{{ getSettings()->mainMediaUrl('logo') }}" alt="" />
            </div>
            <div> 
                <span class="btn blue btn-outline btn-file">
                    <span class="fileinput-new btn btn-success"> @lang('main.select_image') </span>
                    <span class="fileinput-exists btn btn-info"> @lang('main.change') </span>
                    <input type="file" name="logo" id="logo">
                </span>
                <a href="javascript:;" class="btn btn-danger fileinput-exists" data-dismiss="fileinput"> @lang('main.remove') </a>
            </div>
        </div>
        
        @error('logo')
            <div class="invalid-feedback d-block">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
 
 
{{-- Slider --}}
{{-- <div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="slider">@lang('main.slider')</label>
    <div class="col-sm-10">
        <input type="file" name="slider_images[]" id="image" multiple>

        @error('slider')
            <div class="invalid-feedback d-block">
                {{ $message }}
            </div>
        @enderror
    </div>
</div> --}}

{{-- NOTE: Change This Design to mansory layout, Already Found In Portfolio --}}

{{-- <section class="mb-5">
    <div class="grid d-flex">
        @php
            $media = getSettings()->allMedia('slider_images');
        @endphp
    
        <div class="grid-col grid-col--1"></div>
        <div class="grid-col grid-col--2 d-sm-none d-md-block"></div>
        <div class="grid-col grid-col--3 d-sm-none d-lg-block"></div>
        <div class="grid-col grid-col--4"></div>
        
        @foreach ($media as $image)
            <div class="card grid-item image-container">
                <img class="card-img settings-image" src="{{ $image->getUrl() }}"> 
                    
                <button type="button" class="btn btn-danger btn-sm delete-image" data-id="{{ $image->id }}">
                    <i class="fas fa-trash"></i>
                </button>
    
            </div>
        @endforeach
    </div>
</section> --}}