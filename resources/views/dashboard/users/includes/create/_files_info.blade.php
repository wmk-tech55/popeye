{{-- ID Card --}}
{{-- <div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="id_card">@lang('main.id_card')</label>
    <div class="col-sm-10">
        <input type="file" name="id_card" id="id_card" accept=".pdf">

        @error('id_card')
            <div class="invalid-feedback d-block">
                {{ $message }}
            </div>
        @enderror
    </div>
</div> --}}
 
{{-- Business Register  --}}
<div class="form-group mb-4 company-only d-none row">
    <label class="col-sm-2 col-form-label" for="commercial_license">@lang('main.commercial_license')</label>
    <div class="col-sm-10">
        <input type="file" name="commercial_license" id="commercial_license" accept=".pdf,.png,.jpg,.jpeg">
        
        @error('commercial_license')
            <div class="invalid-feedback d-block">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>