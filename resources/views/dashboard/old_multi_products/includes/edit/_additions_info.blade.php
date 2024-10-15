 <div class="form-group mb-4 row">
     <label class="col-sm-2 col-form-label" for="additions">@lang('main.additions') </label>
     <div class="col-sm-10">

         <div class="custom-control custom-radio custom-control-inline">
             <input type="radio" id="yes" name="has_additions" value="yes"
                 {{ $product->has_additions ? 'checked' : '' }} class="custom-control-input">
             <label class="custom-control-label" for="yes">@lang('main.yes')</label>
         </div>

         <div class="custom-control custom-radio custom-control-inline">
             <input type="radio" id="no" name="has_additions" value="no"
                 {{ !$product->has_additions ? 'checked' : '' }} class="custom-control-input">
             <label class="custom-control-label" for="no">@lang('main.no')</label>
         </div>
         @error('type')
             <div class="invalid-feedback">
                 {{ $message }}
             </div>
         @enderror

     </div>
 </div>

 <hr>

 {{-- Additions --}}
 <div class="form-group mb-4 row d-none additions-options-section">

     <div class="col-sm-10">

         <div class="special-price">

             <div id="newAdditionRowContainer">

                 <div class="col-sm-2 d-inline-block"></div>

                 @if ($product->has_additions)
                     @foreach ($product->productAdditions as $addition)
                         <div class="additions-container">

                             <div class="col-sm-3 d-inline-block">
                                 <input type="text" name="additions_ar_names[]" value="{{ $addition->ar_name }}"
                                     class="form-control mb-1 additions-price-inputs @error("additions_ar_names.{$loop->index}") is-invalid @enderror"
                                     placeholder="@lang('main.ar_name') *">
                             </div>
                             <div class="col-sm-3 d-inline-block">
                                 <input type="text" name="additions_en_names[]" value="{{ $addition->en_name }}"
                                     class="form-control mb-1 additions-price-inputs @error("additions_en_names.{$loop->index}") is-invalid @enderror"
                                     placeholder="@lang('main.en_name') *">
                             </div>
                             <div class="col-sm-3 d-inline-block">
                                 <input type="number" name="additions_prices[]" value="{{ $addition->price }}"
                                     class="form-control mb-1 additions-price-inputs @error("additions_prices.{$loop->index}") is-invalid @enderror"
                                     placeholder="@lang('main.price') *">
                             </div>

                             <div class="col-sm-2 d-inline-block">
                                 <button id="removeAdditionsRow" type="button" class="btn btn-danger btn-sm">
                                     <i class="fas fa-trash"></i>
                                 </button>
                             </div>

                         </div>
                     @endforeach
                 @else
                     <div class="additions-container">

                         <div class="col-sm-3 d-inline-block">
                             <input type="text" name="additions_ar_names[]" value="{{ old('additions_ar_names.0') }}"
                                 class="form-control mb-1 additions-price-inputs @error('additions_ar_names.0') is-invalid @enderror"
                                 placeholder="@lang('main.ar_name') *">
                         </div>
                         <div class="col-sm-3 d-inline-block">
                             <input type="text" name="additions_en_names[]" value="{{ old('additions_en_names.0') }}"
                                 class="form-control mb-1 additions-price-inputs @error('additions_en_names.0') is-invalid @enderror"
                                 placeholder="@lang('main.en_name') *">
                         </div>
                         <div class="col-sm-3 d-inline-block">
                             <input type="number" name="additions_prices[]" value="{{ old('additions_prices.0') }}"
                                 class="form-control mb-1 additions-price-inputs @error('additions_prices.0') is-invalid @enderror"
                                 placeholder="@lang('main.price') *">
                         </div>

                     </div>
                 @endif
             </div>

             <button id="newAdditionRow" type="button" class="btn btn-info mb-1 mt-1 btn-sm">
                 <i class="fas fa-plus"></i>
                 @lang('main.add')
             </button>

             @error('additions')
                 <div class="invalid-feedback">
                     {{ $message }}
                 </div>
             @enderror
         </div>
     </div>
 </div>
