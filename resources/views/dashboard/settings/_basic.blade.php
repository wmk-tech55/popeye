{{-- Name --}}
<div class="form-group my-4 row">
    <label class="col-sm-2 col-form-label" for="name">@lang('main.name')<span class="required"></span> </label>
    <div class="col-sm-10">
        <input type="text" name="name" value="{{ $settings->name }}" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="@lang('main.name')" required>
        @error('name')
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
        <input type="email" name="email" value="{{ $settings->email }}" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="@lang('main.email')" required>
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
        <input type="text" name="phone" value="{{ $settings->phone }}" id="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="@lang('main.phone')" required>
        @error('phone')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>

{{-- Address --}}
<div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="address">@lang('main.address')<span class="required"></span> </label>
    <div class="col-sm-10">
        <input type="text" name="address" value="{{ $settings->address }}" id="address" class="form-control @error('address') is-invalid @enderror" placeholder="@lang('main.address')" required>
        @error('address')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>

 