{{-- Price --}}
<div class="form-group mb-4 row ">
 

    <div class="col-sm-12">

        <div class="special-price">

            <div id="newAdditionRowContainer">

                <div class="col-sm-2 d-inline-block"></div>

                <div class="additions-container">

                    <div class="col-sm-3 d-inline-block">
                        <input type="text" name="additions_ar_names[]" value="{{ old('ar_names') }}"
                            class="form-control mb-1 special-price-inputs @error('ar_names') is-invalid @enderror"
                            placeholder="@lang('main.ar_name') *">
                    </div>
                    <div class="col-sm-3 d-inline-block">
                        <input type="text" name="additions_en_names[]" value="{{ old('en_names') }}"
                            class="form-control mb-1 special-price-inputs @error(' en_names') is-invalid @enderror"
                            placeholder="@lang('main.en_name') *">
                    </div>
                    <div class="col-sm-3 d-inline-block">
                        <input type="number" name="additions_prices[]" value="{{ old('prices') }}"
                            class="form-control mb-1 special-price-inputs @error(' prices') is-invalid @enderror"
                            placeholder="@lang('main.price') *">
                    </div>

                </div>

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
