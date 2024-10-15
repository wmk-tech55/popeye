@extends('dashboard.layouts.app')

@section('section', $sectionName)

@section('styles')
<style>
    .map-container {
        height: 500px;
    }

    .grid-col {
        flex: 1;
        padding: 0 .1em;
    }

    form {
        margin: 15px;
    }
</style>

@endsection

@section('content')


<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{ $sectionName }}</h1>
    <div>
        <a href="{{ route('dashboard.companies.edit', $company) }}"
            class="d-sm-inline-block btn btn-sm btn-info shadow-sm">
            <i class="fas fa-edit fa-sm text-white-50"></i>
            @lang('main.edit')
        </a>

        <a href="{{ route('dashboard.companies.destroy',  $company) }}"
            class="d-sm-inline-block btn btn-sm btn-danger shadow-sm" data-toggle="modal"
            data-target="#deleteModel-{{ $company->id }}" title="@lang('main.delete')">
            <i class="fas fa-trash fa-sm text-white-50"></i>
            @lang('main.delete')
        </a>

        <a href="{{ route('dashboard.companies.create') }}" class="d-sm-inline-block btn btn-sm btn-success shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i>
            @lang('main.add') @lang('main.companies')
        </a>

        <a href="{{ route('dashboard.companies.index') }}" class="d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-users fa-sm text-white-50"></i>
            @lang('main.show_all') @lang('main.companies')
        </a>

        @component('dashboard.components.deleteModelForm')
        @slot('id', $company->id )
        @slot('deleteTitle', trans('main.company') . ' ' . $company->full_name)
        @slot('url', route('dashboard.companies.destroy', $company->id) )
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
                    <div class="card-title col-md-6 col-sm-12 mb-0">@lang('main.created_at'): {{
                        $company->created_at->calendar() }} </div>
                    <div class="card-title col-md-6 col-sm-12 mb-0">@lang('main.updated_at'): {{
                        $company->updated_at->calendar() }} </div>
                </div>
                <hr>
                <div class="card-text">
                    <div class="row">
                        @include('dashboard.companies.includes.show._basic_info')
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>


<!-- categorizations Content Row -->
{{-- <div class="row">
    <div class="col-xl-1 col-md-1"></div>
    <div class="col-xl-12 col-md-12 col-sm-12">
        <div class="card mb-3">
            <div class="card-body border-left-warning">
                <div class="card-title font-weight-bold h4">@lang('main.categorizations')</div>
                <hr>
                <div class="card-text">
                    <div class="row">
                        @include('dashboard.companies.includes.show._categorizations')
                    </div>
                </div>

            </div>
        </div>
    </div>
</div> --}}

<!-- Files Content Row -->
<div class="row">
    <div class="col-xl-1 col-md-1"></div>
    <div class="col-xl-12 col-md-12 col-sm-12">
        <div class="card mb-3">
            <div class="card-body border-left-primary">
                <div class="card-title font-weight-bold h4">@lang('main.files')</div>
                <hr>
                <div class="card-text">
                    <div class="row">
                        @php
                        $media = $company->UserMedia();
                        @endphp

                        @include('dashboard.companies.includes.show._files_info')
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- location Content Row -->
 <div class="row">
    <div class="col-xl-1 col-md-1"></div>
    <div class="col-xl-12 col-md-12 col-sm-12">
        <div class="card mb-3">
            <div class="card-body border-left-danger">
                <div class="card-title font-weight-bold h4">@lang('main.address')</div>
                <hr>
                <div class="card-text">
                    <div class="row">
                        @include('dashboard.companies.includes.show._location_info')
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>  

 


@endsection

@section('scripts')
{{-- Google Apis --}}
<script type="text/javascript"
    src="https://maps.googleapis.com/maps/api/js?key={{config('defaults.system.google_map_key')}}&libraries=places"></script>


{{-- Map Init --}}
<script>
    var geoLocation = {
            lat: {{ !! $company->latitude ? $company->latitude : 0 }}, 
            lng: {{ !! $company->longitude ? $company->longitude : 0 }},
        };

        var map = new google.maps.Map(document.getElementById('map'), {
            center: geoLocation,
            zoom: {{ !! $company->zoom ? $company->zoom : 0 }},
        });

        var marker = new google.maps.Marker({
            position: geoLocation,
            animation: google.maps.Animation.BOUNCE,
            map: map,
            draggable : false,
            title: '{{ $company->name_by_lang }}'
        });
</script>

@endsection