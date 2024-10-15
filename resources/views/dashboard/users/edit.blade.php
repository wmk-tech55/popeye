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

    <div>
        <a href="{{ route('dashboard.users.show', $user) }}" class="d-sm-inline-block btn btn-sm btn-info shadow-sm">
            <i class="fas fa-eye fa-sm text-white-50"></i>
            @lang('main.show') {{ $user->full_name }}
        </a>

        <a href="{{ route('dashboard.users.index') }}" class="d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-users fa-sm text-white-50"></i>
            @lang('main.show_all') @lang('main.users')
        </a>

    </div>
</div>

<!-- Content Row -->
<div class="row mb-3">
    <div class="col-xl-12 col-md-12 col-sm-12">
        <form action="{{ route('dashboard.users.update', $user) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="card mb-3">
                <div class="card-body border-left-info">

                    <div class="card-title font-weight-bold h5 text-center text-info">{{ $sectionName }}</div>

                    <div class="card-title font-weight-bold h4 text-muted">@lang('main.basic')</div>
                    <div class="card-text">
                        @include('dashboard.users.includes.edit._basic_info')
                        <hr>
                        @include('dashboard.users.includes.edit._countries')
                        <hr>
                        @include('dashboard.users.includes.edit._type_and_status_info')
                    </div>
                </div>
            </div>
 

            <div class="card">
                <div class="card-body border-left-info">
                    <div class="form-group row">
                        <div class="col-sm-10">
                            <button type="submit" class="btn btn-info">@lang('main.edit') @lang('main.user')</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

</div>

<div class="row">
    <div class="col-xl-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-body border-left-danger">

                <form action="{{ route('dashboard.users.update.password', $user) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    {{-- Password --}}
                    <div class="form-group mb-4 row">
                        <label class="col-sm-2 col-form-label" for="password">@lang('main.password')<span
                                class="required"></span> </label>
                        <div class="col-sm-10">
                            <input type="password" name="password" id="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="@lang('main.password')" required>
                            @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-10">
                            <button type="submit" class="btn btn-danger">@lang('main.edit')
                                @lang('main.password')</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>


@endsection

@section('scripts')
{{-- Bootstrap file input --}}
<script src="{{ asset('/dashboard_assets/libs/bootstrap-fileinput/bootstrap-fileinput.js') }}" type="text/javascript">
</script>

{{-- Select2 --}}
<script src="{{ asset('/dashboard_assets/libs/select2/js/select2.min.js') }}" type="text/javascript"></script>

@endsection