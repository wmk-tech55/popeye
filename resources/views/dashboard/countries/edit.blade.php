@extends('dashboard.layouts.app')

@section('section', $sectionName)

@section('styles')
    {{-- Bootstrap file input --}}
    <link href="{{ asset('/dashboard_assets/libs/bootstrap-fileinput/bootstrap-fileinput.css') }}" rel="stylesheet"
        type="text/css" />

    {{-- Bootstrap Switch --}}
    <link href="{{ asset('/dashboard_assets/libs/bootstrap-switch/css/bootstrap-switch' . getRtlDirection() . '.min.css') }}"
        rel="stylesheet" type="text/css" />
@endsection

@section('content')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $sectionName }}</h1>

        <div>
            <a href="{{ route('dashboard.countries.show', $country) }}"
                class="d-sm-inline-block btn btn-sm btn-info shadow-sm">
                <i class="fas fa-eye fa-sm text-white-50"></i>
                @lang('main.show') {{ $country->name_by_lang }}
            </a>

            <a href="{{ route('dashboard.countries.index') }}" class="d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-globe fa-sm text-white-50"></i>
                @lang('main.show_all') @lang('main.countries')
            </a>

        </div>
    </div>

    <!-- Content Row -->
    <div class="row mb-3">

        <div class="col-xl-1 col-md-1"></div>
        <div class="col-xl-12 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body border-left-info">
                    <div class="card-title font-weight-bold h5 text-center text-info">{{ $sectionName }}</div>
                    <div class="card-text">
                        <form action="{{ route('dashboard.countries.update', $country) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')


                            <div class="row mb-3">
                                <h1 class=" col-xl-12 col-md-12 h4 font-weight-bold mb-0 text-gray-800">@lang('main.basic')</h1>
                            </div>

                            @include('dashboard.countries.includes.edit._basic_info')

                            <hr>
                            <div class="row mb-3">
                                <h1 class=" col-xl-12 col-md-12 h4 font-weight-bold mb-0 text-gray-800">@lang('main.shipping_fee')</h1>
                            </div>
                            @include('dashboard.countries.includes.edit._shipping_per_distance')

                            <hr>

                            @include('dashboard.countries.includes.edit._status')
                            {{-- @include('dashboard.countries.includes.edit._image') --}}
                            
                            <div class="form-group row">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-info">@lang('main.edit')
                                        @lang('main.country')</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

    </div>

@endsection


@section('scripts')
    {{-- Bootstrap file input --}}
    <script src="{{ asset('/dashboard_assets/libs/bootstrap-fileinput/bootstrap-fileinput.js') }}" type="text/javascript">
    </script>

    {{-- Bootstrap Switch --}}
    <script src="{{ asset('/dashboard_assets/libs/bootstrap-switch/js/bootstrap-switch.js') }}" type="text/javascript">
    </script>
@endsection
