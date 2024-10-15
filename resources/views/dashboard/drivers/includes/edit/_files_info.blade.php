 {{-- vehicle_license --}}
 <div class="form-group mb-4 row">
     <label class="col-sm-2 col-form-label" for="vehicle_license">@lang('main.vehicle_license')</label>
     <div class="col-sm-10">

         <div class="alert alert-warning">
             <i class="fas fa-exclamation-triangle"></i>
             @lang('main.pdf_jpg_png_images_only')
         </div>


         <div class="fileinput fileinput-new" data-provides="fileinput">
             <div class="fileinput-preview thumbnail" data-trigger="fileinput">


                 @if (pathinfo($driver->allUserMedia['vehicle_license'], PATHINFO_EXTENSION) == 'pdf')
                     <embed src="{{ $driver->allUserMedia['vehicle_license'] }}" width="300" height="300">
                 @else
                     <img src="{{ $driver->allUserMedia['vehicle_license'] }}" alt="{{ $driver->full_name }}" />
                 @endif


             </div>
             <div>
                 <span class="btn blue btn-outline btn-file">
                     <span class="fileinput-new btn btn-success"> @lang('main.select_file') </span>
                     <span class="fileinput-exists btn btn-info"> @lang('main.change') </span>
                     <input type="file" name="vehicle_license" id="vehicle_license">
                 </span>
                 <a href="javascript:;" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">
                     @lang('main.remove') </a>
             </div>
         </div>

         @error('vehicle_license')
             <div class="invalid-feedback d-block">
                 {{ $message }}
             </div>
         @enderror
     </div>
 </div>


 {{-- vehicle_photos --}}
 <div class="form-group mb-4 row">
     <label class="col-sm-2 col-form-label" for="vehicle_photos">@lang('main.vehicle_photos')</label>
     <div class="col-sm-10">
         <input type="file" name="vehicle_photos[]" id="vehicle_photos" multiple>

         @error('vehicle_photos')
             <div class="invalid-feedback d-block">
                 {{ $message }}
             </div>
         @enderror
     </div>
 </div>
 {{-- Media --}}
 <div class="col-xl-12 col-md-12 col-sm-12">
     @include('dashboard.drivers.includes.show._files_info')
 </div>
