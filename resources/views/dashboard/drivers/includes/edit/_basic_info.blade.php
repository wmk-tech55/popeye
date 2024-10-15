{{-- Username --}}
{{-- <div class="form-group my-4 row">
    <label class="col-sm-2 col-form-label" for="username">@lang('main.username')<span class="required"></span> </label>
    <div class="col-sm-10">
        <input type="text" name="username" value="{{ $driver->username }}" id="username" class="form-control @error('username') is-invalid @enderror" placeholder="@lang('main.username')" required>
        @error('username')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div> --}}

{{-- Full Name --}}
<div class="form-group my-4 row">
    <label class="col-sm-2 col-form-label" for="full_name">@lang('main.full_name')<span class="required"></span> </label>
    <div class="col-sm-10">
        <input type="text" name="full_name" value="{{ $driver->full_name }}" id="full_name" class="form-control @error('full_name') is-invalid @enderror" placeholder="@lang('main.full_name')" required>
        @error('full_name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>

{{-- Email --}}
<div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="email">@lang('main.email')<span class="required"></span> </label>
    <div class="col-sm-10">
        <input type="email" name="email" value="{{ $driver->email }}" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="@lang('main.email')" required>
        @error('email')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>

{{-- Phone --}}
<div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="phone">@lang('main.phone')<span class="required"></span> </label>
    <div class="col-sm-10">
        <input type="text" name="phone" value="{{ $driver->phone }}" id="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="@lang('main.phone')" required>
        @error('phone')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>

{{-- Type --}}
<input type="hidden" value="{{$type}}" name="type" />


{{-- Status --}}
<div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="status">@lang('main.status')<span class="required"></span> </label>
    <div class="col-sm-10">
        <select name="status" id="status" class="form-control select2 @error('status') is-invalid @enderror" data-placeholder="@lang('main.status')" required>
            <option value="" disabled selected>@lang('main.status')</option>
            @foreach ($driverStatus as $status)
                <option value="{{ $status }}" {{ $driver->status == $status ? 'selected' : '' }}> @lang("main.{$status}") </option>
            @endforeach
        </select>
        @error('status')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>

{{-- Logo --}}
{{-- <div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="logo">@lang('main.logo')</label>
    <div class="col-sm-10">

        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            @lang('main.jpg_png_images_only')
        </div>


        <div class="fileinput fileinput-new" data-provides="fileinput">
            <div class="fileinput-preview thumbnail" data-trigger="fileinput">
                <img src="{{ $driver->mainMediaUrl('logo') }}" alt="{{ $driver->username }}" /> 
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
</div> --}}
