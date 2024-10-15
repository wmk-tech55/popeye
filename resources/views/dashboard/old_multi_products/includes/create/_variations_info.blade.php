 <div class="form-group mb-4 row">
     <label class="col-sm-2 col-form-label" for="type">@lang('main.product_type')<span class="required"></span> </label>
     <div class="col-sm-10">
      
         <div class="custom-control custom-radio custom-control-inline type">
             <input type="radio" id="customRadio1" name="type" value="one_option"
                 {{ old('type', 'one_option') === 'one_option' ? 'checked' : '' }} class="custom-control-input">
             <label class="custom-control-label" for="customRadio1">@lang('main.one_option')</label>
         </div>

         <div class="custom-control custom-radio custom-control-inline type">
             <input type="radio" id="customRadio2" name="type" value="multiple_options"
                 {{ old('type', 'one_option') === 'multiple_options' ? 'checked' : '' }} class="custom-control-input">
             <label class="custom-control-label" for="customRadio2">@lang('main.multiple_options')</label>
         </div>
         @error('type')
             <div class="invalid-feedback">
                 {{ $message }}
             </div>
         @enderror

     </div>
 </div>
 
 <hr>
 {{-- one option --}}
 <div class="form-group mb-4 row one-option-section">
     <label class="col-sm-2 col-form-label" for="price">@lang('main.price') <span class="required"></span>
     </label>

     <div class="col-sm-10">
         <input type="text" name="price" value="{{ old('price') }}" id="price"
             class="form-control @error('price') is-invalid @enderror" placeholder="@lang('main.price')" required>
         @error('price')
             <div class="invalid-feedback">
                 {{ $message }}
             </div>
         @enderror
     </div>
 </div>
 
 {{-- multiple options --}}
 <div class="form-group mb-4 row d-none multiple-options-section">
     <label class="col-sm-2 col-form-label" for="options">@lang('main.product_variations') <span class="required"></span>
     </label>

     <div class="col-sm-10">

         <div class="special-price  " id="special-price">

             <div id="addVariationRowData">


                 <div class="col-sm-2 d-inline-block"></div>


                 @if ($errors->has('variations_prices.*') ||
                     $errors->has('variations_ar_names.*') ||
                     $errors->has('variations_en_names.*'))

                     @for ($i = 0; $i < count(old('variations_prices')); $i++)
                         <div class="special-price-container">
                             <div class="col-sm-3 d-inline-block">
                                 <input type="text" name="variations_ar_names[]"
                                     value="{{ isset(old('variations_ar_names')[$i]) ? old('variations_ar_names')[$i] : '' }}"
                                     class="form-control mb-1 special-price-inputs @error("variations_ar_names.{$i}") is-invalid @enderror"
                                     placeholder="@lang('main.ar_name') *">
                             </div>
                             <div class="col-sm-3 d-inline-block">
                                 <input type="text" name="variations_en_names[]"
                                     value="{{ isset(old('variations_en_names')[$i]) ? old('variations_en_names')[$i] : '' }}"
                                     class="form-control mb-1 special-price-inputs @error("variations_en_names.{$i}") is-invalid @enderror"
                                     placeholder="@lang('main.en_name') *">
                             </div>
                             <div class="col-sm-3 d-inline-block">
                                 <input type="number" name="variations_prices[]"
                                     value="{{ isset(old('variations_prices')[$i]) ? old('variations_prices')[$i] : '' }}"
                                     class="form-control mb-1 special-price-inputs @error("variations_prices.{$i}") is-invalid @enderror"
                                     placeholder="@lang('main.price') *">
                             </div>

                             <div class="col-sm-2 d-inline-block">
                                 <button id="removeRow" type="button" class="btn btn-danger btn-sm">
                                     <i class="fas fa-trash"></i>
                                 </button>
                             </div>
                         </div>
                     @endfor
                 @else
                     <div class="special-price-container">
                         <div class="col-sm-3 d-inline-block">
                             <input type="text" name="variations_ar_names[]" value="{{ old('variations_ar_names.0') }}"
                                 class="form-control mb-1 special-price-inputs @error('variations_ar_names.0') is-invalid @enderror"
                                 placeholder="@lang('main.ar_name') *">
                         </div>
                         <div class="col-sm-3 d-inline-block">
                             <input type="text" name="variations_en_names[]" value="{{ old('variations_en_names.0') }}"
                                 class="form-control mb-1 special-price-inputs @error('variations_en_names.0') is-invalid @enderror"
                                 placeholder="@lang('main.en_name') *">
                         </div>
                         <div class="col-sm-3 d-inline-block">
                             <input type="number" name="variations_prices[]" value="{{ old('variations_prices.0') }}"
                                 class="form-control mb-1 special-price-inputs @error('variations_prices.0') is-invalid @enderror"
                                 placeholder="@lang('main.price') *">
                         </div>
                     </div>
                 @endif


             </div>

             <button id="addVariationRow" type="button" class="btn btn-info mb-1 mt-1 btn-sm">
                 <i class="fas fa-plus"></i>
                 @lang('main.add')
             </button>

             @error('has_special_price')
                 <div class="invalid-feedback">
                     {{ $message }}
                 </div>
             @enderror
         </div>
     </div>
 </div>
