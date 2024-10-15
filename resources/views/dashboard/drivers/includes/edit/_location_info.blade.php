  {{-- Address --}}
  <div class="form-group mb-4 row">
      <label class="col-sm-2 col-form-label" for="address">@lang('main.address')</label>
      <div class="col-sm-10">
          <input type="text" name="address" id="address" value="{{ $driver->address }}"
              class="form-control @error('address') is-invalid @enderror" placeholder="@lang('main.address')">

          @error('address')
              <div class="invalid-feedback">
                  {{ $message }}
              </div>
          @enderror
      </div>
  </div>


  {{-- Google Map --}}
  <div class="form-group mb-4 row map-container">
      <div class="col-sm-12">
          {{-- Map --}}
          <div id="map-container">
              <div id="map" style="height: 100%"></div>
          </div>

          {{-- Longitude --}}
          <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $driver->longitude) }}">
          {{-- Latitude --}}
          <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $driver->latitude) }}">
          {{-- country --}}
          <input type="hidden" name="country" id="country" value="{{ old('country', $driver->country) }}">
          {{-- city --}}
          <input type="hidden" name="city" id="city" value="{{ old('city', $driver->city) }}">
          {{-- area --}}
          <input type="hidden" name="area" id="area" value="{{ old('area', $driver->area) }}">
          {{-- country_code --}}
          <input type="hidden" name="country_code" id="country_code"
              value="{{ old('country_code', $driver->country_code) }}">
          {{-- Zoom --}}
          <input type="hidden" name="zoom" id="zoom" value="{{ old('zoom', $driver->zoom) }}">
      </div>
  </div>

  {{-- countries --}}
  {{-- <div class="form-group mb-4 row">
      <label class="col-sm-2 col-form-label" for="country_id">@lang('main.countries')<span class="required"></span>
      </label>
      <div class="col-sm-10">
          <select name="country_id" id="country_id"
              class="form-control select2 @error('country_id') is-invalid @enderror"
              data-placeholder="@lang('main.countries')" required>
              @foreach ($countries as $country)
                  <option value="{{ $country->id }}" {{ $country->id == $driver->country_id ? 'selected' : '' }}>
                      {{ $country->name_by_lang }}
                  </option>
              @endforeach
          </select>

          @error('country_id')
              <div class="invalid-feedback">
                  {{ $message }}
              </div>
          @enderror
      </div>
  </div>

  <div class="form-group mb-4 row">
      <label class="col-sm-2 col-form-label" for="city_id">@lang('main.cities')<span class="required"></span>
      </label>
      <div class="col-sm-10">
          <select name="city_id" id="city_id" class="form-control select2 @error('city_id') is-invalid @enderror"
              data-placeholder="@lang('main.cities')" required>
              @foreach ($cities as $city)
                  <option value="{{ $city->id }}" {{ $city->id == $driver->city_id ? 'selected' : '' }}>
                      {{ $city->name_by_lang }}
                  </option>
              @endforeach
          </select>

          @error('city_id')
              <div class="invalid-feedback">
                  {{ $message }}
              </div>
          @enderror
      </div>
  </div> --}}
