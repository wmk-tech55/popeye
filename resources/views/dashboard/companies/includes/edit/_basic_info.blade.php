{{-- Full Name --}}

<div class="form-group my-4 row">
    <label class="col-sm-2 col-form-label" for="full_name">@lang('main.full_name')<span class="required"></span>
    </label>
    <div class="col-sm-10">
        <input type="text" name="full_name" value="{{ $company->full_name }}" id="full_name"
            class="form-control @error('full_name') is-invalid @enderror" placeholder="@lang('main.full_name')" required>
        @error('full_name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>



{{-- Email --}}
<div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="email">@lang('main.email')  </label>
    <div class="col-sm-10">
        <input type="email" name="email" value="{{ $company->email }}" id="email"
            class="form-control @error('email') is-invalid @enderror" placeholder="@lang('main.email')"  >
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
        <input type="text" name="phone" value="{{ $company->phone }}" id="phone"
            class="form-control @error('phone') is-invalid @enderror" placeholder="@lang('main.phone')" required>
        @error('phone')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
