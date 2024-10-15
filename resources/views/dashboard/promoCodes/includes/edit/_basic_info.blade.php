 
{{-- discount --}}
<div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="discount">@lang('main.discount')<span class="required"></span> </label>
    <div class="col-sm-10">
        <input type="number" min="1"  name="discount" value="{{ $promoCode->discount }}" id="discount" class="form-control @error('discount') is-invalid @enderror" placeholder="@lang('main.discount')" required>
        @error('discount')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>


{{-- number_of_uses --}}
<div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="number_of_uses">@lang('main.number_of_uses')<span class="required"></span> </label>
    <div class="col-sm-10">
        <input type="number" min="1"  name="number_of_uses" value="{{ $promoCode->number_of_uses }}" id="number_of_uses" class="form-control @error('number_of_uses') is-invalid @enderror" placeholder="@lang('main.number_of_uses')" required>
        @error('number_of_uses')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
 
{{-- Date --}}
<div class="form-group custom-date   mb-4 row">
    <label class="col-sm-2 col-form-label input-group date" for="expiry_date">@lang('main.expiry_date')</label>
    <div class="col-sm-10" >
        
        <input type="text" name="expiry_date" value="{{ $promoCode->expiry_date }}" id="expiry_date"
            class="form-control  expiry_date @error('expiry_date') is-invalid @enderror"
            placeholder="@lang('main.expiry_date')"   autocomplete="off">
          
        @error('expiry_date')
        <div class="invalid-feedback d-block">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>


  
 