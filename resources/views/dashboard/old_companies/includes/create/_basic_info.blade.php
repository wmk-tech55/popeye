{{-- Full Name --}}
<div class="form-group my-4 row">
    <label class="col-sm-2 col-form-label" for="full_name">@lang('main.full_name')<span class="required"></span>
    </label>
    <div class="col-sm-10">
        <input type="text" name="full_name" value="{{ old('full_name') }}" id="full_name"
            class="form-control @error('full_name') is-invalid @enderror" placeholder="@lang('main.full_name')"
            required>
        @error('full_name')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>

{{-- comapny name --}}
<div class="form-group my-4 row">
    <label class="col-sm-2 col-form-label" for="username">@lang('site.company_name')<span class="required"></span> </label>
    <div class="col-sm-10">
        <input type="text" name="username" value="{{ old('username') }}" id="username" class="form-control @error('username') is-invalid @enderror" placeholder="@lang('site.company_name')" required>
        @error('username')
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
        <input type="email" name="email" value="{{ old('email') }}" id="email"
            class="form-control @error('email') is-invalid @enderror" placeholder="@lang('main.email')" required>
        @error('email')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>

{{-- Password --}}
<div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="password">@lang('main.password')<span class="required"></span> </label>
    <div class="col-sm-10">
        <input type="password" name="password" value="{{ old('password') }}" id="password"
            class="form-control @error('password') is-invalid @enderror" placeholder="@lang('main.password')" required>
        @error('password')
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
        <input type="text" name="phone" value="{{ old('phone') }}" id="phone"
            class="form-control @error('phone') is-invalid @enderror" placeholder="@lang('main.phone')" required>
        @error('phone')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>


<input type="hidden" value="{{$type}}" id="type" name="type" />
