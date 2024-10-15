<li class="nav-item dropdown no-arrow" style="width: 150px;margin: auto;">
    <select class="form-control select2" id="active_country_id">
        <option disabled selected> @lang('main.country')</option>
        @foreach ($countries as $country)
            <option value="{{ $country->id }}" data-link="{{ route('dashboard.users.changeCountry', $country->id) }} "
                {{ auth()->user()->active_country_id == $country->id ? 'selected' : '' }}> {{ $country->name_by_lang }}</option>
        @endforeach
    </select>
</li>
