<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover text-center">
        <thead>
            <tr>
                <th>@lang('main.day')</th>
                <th>@lang('main.day_is_vacation')</th>
                <th>@lang('main.open_time')</th>
                <th>@lang('main.close_time')</th>
                <th>@lang('main.edit')</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($company->work_times as $work_time)
                <tr>
                    <td>{{ $work_time->day_name_by_lang }}</td>
                    <td>
                        @if ($work_time->day_is_vacation)
                            <span class="badge badge-success">@lang('main.yes')</span>
                        @else
                            <span class="badge badge-info">@lang('main.no')</span>
                        @endif
                    </td>
                    <td>{{ $work_time->open_time->format('H:i') }}</td>
                    <td>{{ $work_time->close_time->format('H:i') }}</td>
                    <td>
                        <button type="button" class="btn btn-info btn-sm" title="@lang('main.edit')" data-toggle="modal" data-target="#editModel-{{ $work_time->id }}">
                            <i class="fas fa-edit"></i>
                        </button>                        
                    </td>
                </tr>

                @push('before_scripts')
                    @component('dashboard.components.editModelForm') 
                        @slot('id',  $work_time->id ) 
                        @slot('editTitle', $work_time->day_name_by_lang) 
                        @slot('largeModal', true) 
                        @slot('url', route('dashboard.companies.update.work_time', ['company' => $company, 'work_time' => $work_time]) ) 
                        
                        @include('dashboard.companies.includes._work_time_edit_form')
                    @endcomponent
                @endpush

                @push('scripts')
                    {{-- Open Time --}}
                    <script>
                        $('#open_time-{{ $work_time->id }}').timepicker({
                            uiLibrary: 'bootstrap4',
                            modal: true,
                            format:"HH"
                        });
                    </script>

                    {{-- Close Time --}}
                    <script>
                        $('#close_time-{{ $work_time->id }}').timepicker({
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