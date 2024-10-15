 {{-- AR Name --}}
 <div class="form-group mb-4 row">
     <label class="col-sm-2 col-form-label" for="ar_name">@lang('main.name')<span class="required"></span> </label>
     <div class="col-sm-10">
         <input type="text" name="ar_name" value="{{ $product->ar_name }}" id="ar_name"
             class="form-control @error('ar_name') is-invalid @enderror" placeholder="@lang('main.name')" required>
         @error('ar_name')
             <div class="invalid-feedback">
                 {{ $message }}
             </div>
         @enderror
     </div>
 </div>


 {{-- Arabic Overview --}}
 <div class="form-group mb-4 row">
     <label class="col-sm-2 col-form-label" for="ar_overview">@lang('main.overview')<span class="required"></span> </label>
     <div class="col-sm-10">
         <textarea type="text" name="ar_overview" id="ar_overview"
             class="form-control @error('ar_overview') is-invalid @enderror" placeholder="@lang('main.overview')" required>
            {{ $product->ar_overview }}
        </textarea>
         @error('ar_overview')
             <div class="invalid-feedback">
                 {{ $message }}
             </div>
         @enderror
     </div>
 </div>


 <div class="form-group mb-4 row one-option-section">
     <label class="col-sm-2 col-form-label" for="price">@lang('main.price') <span class="required"></span>
     </label>

     <div class="col-sm-10">
         <input type="text" name="price" value="{{ old('price', $product->price) }}" id="price"
             class="form-control @error('price') is-invalid @enderror" placeholder="@lang('main.price')" required>
         @error('price')
             <div class="invalid-feedback">
                 {{ $message }}
             </div>
         @enderror
     </div>
 </div>


 <div class="form-group mb-4 row one-option-section">
     <label class="col-sm-2 col-form-label" for="discount_percentage">@lang('main.discount_percentage')
     </label>
     <div class="col-sm-10">
         <input type="number" name="discount_percentage" 
             value="{{ old('discount_percentage', $product->discount_percentage) }}" id="discount_percentage"
             class="form-control @error('discount_percentage') is-invalid @enderror" placeholder="@lang('main.discount_percentage_note')">
         @error('discount_percentage')
             <div class="invalid-feedback">
                 {{ $message }}
             </div>
         @enderror
     </div>


 </div>
