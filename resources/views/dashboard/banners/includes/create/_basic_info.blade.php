{{-- EN Name --}}
<div class="form-group my-4 row">
    <label class="col-sm-2 col-form-label" for="en_name">@lang('main.en_title')<span class="required"></span> </label>
    <div class="col-sm-10">
        <input type="text" name="en_name" value="{{ old('en_name') }}" id="en_name"
            class="form-control @error('en_name') is-invalid @enderror" placeholder="@lang('main.en_title')" required>
        @error('en_name')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>

{{-- AR Name --}}
<div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="ar_name">@lang('main.ar_title')<span class="required"></span> </label>
    <div class="col-sm-10">
        <input type="text" name="ar_name" value="{{ old('ar_name') }}" id="ar_name"
            class="form-control @error('ar_name') is-invalid @enderror" placeholder="@lang('main.ar_title')" required>
        @error('ar_name')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>

<hr>

{{-- Store --}}
<div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="store_id">@lang('main.company')</label>
    <div class="col-sm-10">
        <select name="store_id" id="store_id" class="form-control select2 @error('store_id') is-invalid @enderror" data-placeholder="@lang('main.company')" >
            <option value="" disabled selected>@lang('main.company')</option>

            @foreach ($stores as $store)
                <option value="{{ $store->id }}" {{ old('store_id') == $store->id ? 'selected' : '' }}> {{ $store->full_name }} </option>
            @endforeach
        </select>
        
        @error('store_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
