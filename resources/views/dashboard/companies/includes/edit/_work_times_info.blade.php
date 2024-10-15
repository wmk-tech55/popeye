<div class="ltn__form-box">

    @php
    $work_times = $company->work_times->toArray() ;
    @endphp

    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover text-center">
            <thead>
                <tr>
                    <th>@lang('main.day')</th>
                    <th>@lang('main.day_is_vacation')</th>
                    <th>@lang('main.open_time')</th>
                    <th>@lang('main.close_time')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($work_times as $work_time)
                <tr>
                    <td>{{ $work_time[getLanguageForAssets().'_day_name'] }}</td>
                    <td>
                        <input type="hidden" name="work_time_id[]" value="{{$work_time['id']}}">
                        <input type="checkbox" {{ $work_time['day_is_vacation'] ? 'checked' : '' }}
                            name="day_is_vacation[]" data-toggle="toggle" data-size="xs" data-on="@lang('main.yes')"
                            data-off="@lang('main.no')" data-onstyle="success" data-offstyle="danger"
                            value="{{$work_time['raw_order']}}">

                        @error('day_is_vacation')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </td>
                    <td>
                        <input type="text" name="open_time[]" value="{{ $work_time['open_time'] }}"
                            id="open_time-{{ $work_time['raw_order'] }}"
                            class="time-picker-style @error('open_time') is-invalid @enderror"
                            placeholder="@lang('main.open_time')" autocomplete="off">

                        @error('open_time')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </td>
                    <td>
                        <input type="text" name="close_time[]" value="{{ $work_time['close_time'] }}"
                            id="close_time-{{ $work_time['raw_order'] }}"
                            class="time-picker-style @error('close_time') is-invalid @enderror"
                            placeholder="@lang('main.close_time')" autocomplete="off">

                        @error('close_time')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </td>

                </tr>



                @push('scripts')
                {{-- Open Time --}}
                <script>
                    $("#open_time-{{ $work_time['raw_order'] }}").timepicker({
                        uiLibrary: 'bootstrap4',
                        modal: true,
                        format:"HH"
                    });
                </script>

                {{-- Close Time --}}
                <script>
                    $("#close_time-{{ $work_time['raw_order'] }}").timepicker({
                        uiLibrary: 'bootstrap4',
                        modal: true,
                        format:"HH"
                    });
                </script>
                @endpush

                @endforeach
            </tbody>
        </table>
    </div>

</div>