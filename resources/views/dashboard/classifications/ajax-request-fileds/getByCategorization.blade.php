<div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="classification_id">@lang('main.classification')<span class="required"></span> </label>
    <div class="col-sm-10">
        <select name="classification_id[]" id="classification_id" class="form-control select2 @error('classification_id') is-invalid @enderror" data-placeholder="@lang('main.classification')" required>
            <option value="" disabled selected>@lang('main.classification')</option>
            @foreach ($classifications as $classification)
                <option value="{{ $classification->id }}" {{ old('classification_id') == $classification->id ? 'selected' : '' }}>
                    {{ $classification->name_by_lang }} 
                </option>
            @endforeach
        </select>

        @error('classification_id')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>