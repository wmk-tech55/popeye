{{-- comapny name --}}
<div class="form-group my-4 row">
    <label class="col-sm-2 col-form-label" for="username">@lang('site.company_name')<span class="required"></span> </label>
    <div class="col-sm-10">
        <input type="text" name="username" value="{{ $company->username }}" id="username"
            class="form-control @error('username') is-invalid @enderror" placeholder="@lang('site.company_name')" required>
        @error('username')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>