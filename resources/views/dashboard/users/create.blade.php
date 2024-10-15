@extends('dashboard.layouts.app')

@section('section', $sectionName)

@section('styles')
    {{-- Bootstrap file input --}}
    <link href="{{ asset('/dashboard_assets/libs/bootstrap-fileinput/bootstrap-fileinput.css') }}" rel="stylesheet"
        type="text/css" />


@endsection

@section('content')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $sectionName }}</h1>
        <a href="{{ route('dashboard.users.index') }}" class="d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-users fa-sm text-white-50"></i>
            @lang('main.show_all') @lang('main.users')
        </a>
    </div>

    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-1 col-md-1"></div>
        <div class="col-xl-12 col-md-12 col-sm-12">
            <form action="{{ route('dashboard.users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="card mb-3">
                    <div class="card-body border-left-info">

                        <div class="card-title font-weight-bold h5 text-center text-info">{{ $sectionName }}</div>

                        <div class="card-title font-weight-bold h4 text-muted">@lang('main.basic')</div>
                        <div class="card-text">
                            @include('dashboard.users.includes.create._basic_info')
                            <hr>
                            @include('dashboard.users.includes.create._countries')
                            <hr>
                            @include('dashboard.users.includes.create._type_and_status_info')
                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-body border-left-info">
                        <div class="form-group row">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-info">@lang('main.add') @lang('main.user')</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>


@endsection

@section('scripts')
    {{-- Bootstrap file input --}}
    <script src="{{ asset('/dashboard_assets/libs/bootstrap-fileinput/bootstrap-fileinput.js') }}" type="text/javascript">
    </script>



@endsection
