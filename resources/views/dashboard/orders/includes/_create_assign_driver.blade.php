<form action="{{ route('dashboard.orders.assign_driver', $order) }}" method="POST" class="status-form">
    @csrf
    @method('PATCH')
    <div class="form-group col-md-12 col-sm-12">

        <div class="col-md-12 col-sm-12">
            <select name="driver_id" id="driver_id"
                class="form-control select2  @error('driver_id') is-invalid @enderror"
                data-placeholder="@lang('main.drivers')">
                <option disabled selected>@lang('main.driver')</option>
                @foreach ($drivers as $driver)
                <option value="{{ $driver->id }}" {{ in_array($driver->id, old('driver_id') ?? []) ? 'selected' : '' }}>
                    {{ $driver->full_name }}
                </option>
                @endforeach
            </select>

            @error('driver_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
    </div>
    <div class="col-md-12 col-sm-12  ">

        <button type="submit" class="btn btn-sm btn-info">@lang('main.add') @lang('main.driver')</button>

    </div>
</form>