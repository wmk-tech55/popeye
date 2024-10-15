{{-- categorizations --}}
<div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="categorization_id">@lang('main.categorizations')<span class="required"></span>
    </label>
    <div class="col-sm-10">
        <select name="categorization_id" id="categorization_id"
            class="form-control select2 @error('categorization_id') is-invalid @enderror"
            data-placeholder="@lang('main.categorizations')"   required>
            @foreach ($categorizations as $categorization)
                <option value="{{ $categorization->id }}"
                    {{  $categorization->id == $company->categorization_id  ? 'selected' : '' }}>
                    {{ $categorization->name_by_lang }}
                </option>
            @endforeach
        </select>

        @error('categorization_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div> 