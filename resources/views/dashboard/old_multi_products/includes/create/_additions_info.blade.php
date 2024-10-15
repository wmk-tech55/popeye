{{--  @dd($errors->all())  --}}
<div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="additions">@lang('main.additions') </label>
    <div class="col-sm-10">

        <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" id="yes" name="has_additions" value="yes"
                {{ old('has_additions', 'no') === 'yes' ? 'checked' : '' }} class="custom-control-input">
            <label class="custom-control-label" for="yes">@lang('main.yes')</label>
        </div>

        <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" id="no" name="has_additions" value="no"
                {{ old('has_additions', 'no') === 'no' ? 'checked' : '' }} class="custom-control-input">
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

                @if ($errors->has('additions_prices.*') ||
                    $errors->has('additions_ar_names.*') ||
                    $errors->has('additions_en_names.*'))

                    @for ($i = 0; $i < count(old('additions_prices')); $i++)
                        <div class="additions-container">
                            <div class="col-sm-3 d-inline-block">
                                <input type="text" name="additions_ar_names[]"
                                    value="{{ isset(old('additions_ar_names')[$i]) ? old('additions_ar_names')[$i] : '' }}"
                                    class="form-control mb-1 special-price-inputs @error("additions_ar_names.{$i}") is-invalid @enderror"
                                    placeholder="@lang('main.ar_name') *">
                            </div>
                            <div class="col-sm-3 d-inline-block">
                                <input type="text" name="additions_en_names[]"
                                    value="{{ isset(old('additions_en_names')[$i]) ? old('additions_en_names')[$i] : '' }}"
                                    class="form-control mb-1 special-price-inputs @error("additions_en_names.{$i}") is-invalid @enderror"
                                    placeholder="@lang('main.en_name') *">
                            </div>
                            <div class="col-sm-3 d-inline-block">
                                <input type="number"
                                    name="additions_prices[]"value="{{ isset(old('additions_prices')[$i]) ? old('additions_prices')[$i] : '' }}"
                                    class="form-control mb-1 special-price-inputs @error("additions_prices.{$i}") is-invalid @enderror"
                                    placeholder="@lang('main.price') *">
                            </div>
                        </div>
                    @endfor
                @else
                    <div class="additions-container">
                        <div class="col-sm-3 d-inline-block">
                            <input type="text" name="additions_ar_names[]" value="{{ old('additions_ar_names.0') }}"
                                class="form-control mb-1 special-price-inputs @error('additions_ar_names.0') is-invalid @enderror"
                                placeholder="@lang('main.ar_name') *">
                        </div>
                        <div class="col-sm-3 d-inline-block">
                            <input type="text" name="additions_en_names[]" value="{{ old('additions_en_names.0') }}"
                                class="form-control mb-1 special-price-inputs @error('additions_en_names.0') is-invalid @enderror"
                                placeholder="@lang('main.en_name') *">
                        </div>
                        <div class="col-sm-3 d-inline-block">
                            <input type="number" name="additions_prices[]" value="{{ old('additions_prices.0') }}"
                                class="form-control mb-1 special-price-inputs @error('additions_prices.0') is-invalid @enderror"
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
