@extends('dashboard.layouts.app')

@section('section', $sectionName)

@section('content')


    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $sectionName }}</h1>
        <div>

            @admin
            @if ($driver->isActive())
                <a href="{{ route('dashboard.drivers.mark_as.pending', $driver) }}"
                    class="d-sm-inline-block btn btn-sm btn-warning shadow-sm">
                    <i class="fas fa-clock fa-sm text-white-50"></i>
                    @lang('main.disable_account')
                </a>
            @else
                <a href="{{ route('dashboard.drivers.mark_as.active', $driver) }}"
                    class="d-sm-inline-block btn btn-sm btn-success shadow-sm">
                    <i class="fas fa-calendar fa-sm text-white-50"></i>
                    @lang('main.mark_as_active')
                </a>
            @endif
        @endadmin
        
            <a href="{{ route('dashboard.drivers.edit', $driver) }}" class="d-sm-inline-block btn btn-sm btn-info shadow-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i>
                @lang('main.edit')
            </a>

            <a href="{{ route('dashboard.drivers.destroy', $driver) }}"
                class="d-sm-inline-block btn btn-sm btn-danger shadow-sm" data-toggle="modal"
                data-target="#deleteModel-{{ $driver->id }}" title="@lang('main.delete')">
                <i class="fas fa-trash fa-sm text-white-50"></i>
                @lang('main.delete')
            </a>

            <a href="{{ route('dashboard.drivers.create') }}" class="d-sm-inline-block btn btn-sm btn-success shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i>
                @lang('main.add') @lang('main.drivers')
            </a>

            <a href="{{ route('dashboard.drivers.index') }}" class="d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-users fa-sm text-white-50"></i>
                @lang('main.show_all') @lang('main.drivers')
            </a>

            @component('dashboard.components.deleteModelForm')
                @slot('id', $driver->id)
                @slot('deleteTitle', trans('main.driver') . ' ' . $driver->full_name)
                @slot('url', route('dashboard.drivers.destroy', $driver->id))
            @endcomponent

        </div>
    </div>

    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-1 col-md-1"></div>
        <div class="col-xl-12 col-md-12 col-sm-12">
            <div class="card mb-3">
                <div class="card-body border-left-info">
                    <div class="card-title font-weight-bold h5 text-center  row">
                        <div class="card-title col-md-6 col-sm-12 mb-0">@lang('main.created_at'):
                            {{ $driver->created_at->calendar() }} </div>
                        <div class="card-title col-md-6 col-sm-12 mb-0">@lang('main.updated_at'):
                            {{ $driver->updated_at->calendar() }} </div>
                    </div>
                    <hr>
                    <div class="card-text">
                        <div class="row">
                            @include('dashboard.drivers.includes.show._basic_info')


                        </div>
                    </div>

                </div>
            </div>
        </div>


        {{-- Bmedia --}}
        <div class="col-xl-12 col-md-12 col-sm-12 mb-3">
            <div class="card border-left-success">

                <div class="card-body">
                    <div class="card-title font-weight-bold h5 text-center text-success">@lang('main.files')</div>
                    <div class="card-text">
                        <div class="row">

                            @include('dashboard.drivers.includes.show._files_info')
 
                        </div>
                    </div>


                </div>
            </div>
        </div>


    </div>

@endsection


@section('scripts')

    {{-- Colcade Init --}}
    <script>
        new Colcade('.grid', {
            columns: '.grid-col',
            items: '.grid-item'
        });
    </script>

    {{-- Delete Media --}}
    <script>
        deleteMedia('.delete-image', '{{ route('dashboard.drivers.media.destroy', $driver) }}');
    </script>

@endsection
