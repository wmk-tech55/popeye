{{-- Price --}}
<div class="form-group mb-4 row  ">
 

    <div class="col-sm-12">

        <div class="special-price  " id="special-price">
           
            <div id="addVariationRowData">


                <div class="col-sm-2 d-inline-block"></div>

                <div class="special-price-container">

                    <div class="col-sm-4 d-inline-block">
                        <input type="text" name="variations_ar_names[]" value="{{ old('variations_ar_names')  }}"
                            class="form-control mb-1 special-price-inputs @error("variations_ar_names") is-invalid @enderror"
                            placeholder="@lang('main.ar_name') *">
                    </div>
                    <div class="col-sm-4 d-inline-block">
                        <input type="text" name="variations_en_names[]" value="{{ old('variations_en_names')  }}"
                            class="form-control mb-1 special-price-inputs @error("variations_en_names") is-invalid @enderror"
                            placeholder="@lang('main.en_name') *">
                    </div>
                    <div class="col-sm-2 d-inline-block">
                        <input type="number" name="variations_prices[]" value="{{ old('variations_prices')  }}"
                            class="form-control mb-1 special-price-inputs @error("variations_prices") is-invalid @enderror"
                            placeholder="@lang('main.price') *">
                    </div>
                 
                
                   
                </div>

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
