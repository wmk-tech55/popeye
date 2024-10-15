{{-- EN Name --}}
 
<div class="form-group my-4 row">
    <label class="col-sm-2 col-form-label" for="en_name">@lang('main.en_name')<span class="required"></span> </label>
    <div class="col-sm-10">
        <input type="text" name="en_name" value="{{ $discount->en_name }}" id="en_name" class="form-control @error('en_name') is-invalid @enderror" placeholder="@lang('main.en_name')" required>
        @error('en_name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>

{{-- AR Name --}}
<div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="ar_name">@lang('main.ar_name')<span class="required"></span> </label>
    <div class="col-sm-10">
        <input type="text" name="ar_name" value="{{ $discount->ar_name }}" id="ar_name" class="form-control @error('ar_name') is-invalid @enderror" placeholder="@lang('main.ar_name')" required>
        @error('ar_name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>



{{-- discount --}}
<div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="discount">@lang('main.discount')<span class="required"></span> </label>
    <div class="col-sm-10">
        <input type="number" min="1"  name="discount" value="{{ $discount->discount }}" id="discount" class="form-control @error('discount') is-invalid @enderror" placeholder="@lang('main.discount')" required>
        @error('discount')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>

  
 