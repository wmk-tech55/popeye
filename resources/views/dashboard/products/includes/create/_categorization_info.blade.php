{{-- categorization --}}
<div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="categorization_id">@lang('main.categorization')<span class="required"></span> </label>
    <div class="col-sm-10">

        <select name="categorization_id" id="categorization_id"
            class="form-control   select2 @error('categorization_id') is-invalid @enderror"
            data-placeholder="@lang('main.categorization')" required>
          <option value="" disabled selected>@lang('main.categorization')</option>  
            @foreach ($categorizations as $categorization)
                <option value="{{ $categorization->id }}" {{$categorization->id == old('categorization_id')? 'selected' : ''}}>
                    {{$categorization->name_by_lang }}
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
