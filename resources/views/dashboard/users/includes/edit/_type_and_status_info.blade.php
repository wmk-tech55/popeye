
{{-- Type --}}
<div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="type">@lang('main.type')<span class="required"></span> </label>
    <div class="col-sm-10">
        <select name="type" id="type" class="form-control select2 @error('type') is-invalid @enderror"
            data-placeholder="@lang('main.type')" required>
            <option value="" disabled selected>@lang('main.type')</option>
            @foreach ($userTypes as $type)
            <option value="{{ $type }}" {{ $user->type == $type ? 'selected' : '' }}> @lang("main.{$type}") </option>
            @endforeach
        </select>
        @error('type')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>

{{-- Status --}}
<div class="form-group mb-4 row">
    <label class="col-sm-2 col-form-label" for="status">@lang('main.status')<span class="required"></span> </label>
    <div class="col-sm-10">
        <select name="status" id="status" class="form-control select2 @error('status') is-invalid @enderror"
            data-placeholder="@lang('main.status')" required>
            <option value="" disabled selected>@lang('main.status')</option>
            @foreach ($userStatus as $status)
            <option value="{{ $status }}" {{ $user->status == $status ? 'selected' : '' }}> @lang("main.{$status}")
            </option>
            @endforeach
        </select>
        @error('status')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
</div>
