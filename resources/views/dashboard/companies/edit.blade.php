@extends('dashboard.layouts.app')

@section('section', $sectionName)

@section('styles')

    {{-- Bootstrap Stepper --}}
    <link href="{{ asset('/dashboard_assets/libs/bs-stepper/css/bs-stepper.min.css') }}" rel="stylesheet" type="text/css" />

    <style>
        #map-container {
            width: 100%;
            height: 50vh;
        }

        #map {
            width: 100%;
            height: 100%;

            top: 0px;
            left: 0px;
        }
    </style>
    {{-- Bootstrap file input --}}
    <link href="{{ asset('/dashboard_assets/libs/bootstrap-fileinput/bootstrap-fileinput.css') }}" rel="stylesheet"
        type="text/css" />



    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">

    {{-- Gijgo Picker --}}
    <link href="{{ asset('/dashboard_assets/libs/gijgo-bootstrap-pickers/css/gijgo.min.css') }}" rel="stylesheet"
        type="text/css" />
@endsection

@section('content')

    {{-- <div class="alert alert-info">
    <i class="fas fa-exclamation-circle"></i>
    @lang('main.default_price_ratio_message')
</div> --}}

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $sectionName }}</h1>

        <div>
            <a href="{{ route('dashboard.companies.show', $company) }}"
                class="d-sm-inline-block btn btn-sm btn-info shadow-sm">
                <i class="fas fa-eye fa-sm text-white-50"></i>
                @lang('main.show') {{ $company->name_by_lang }}
            </a>

            <a href="{{ route('dashboard.companies.index') }}" class="d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plane fa-sm text-white-50"></i>
                @lang('main.show_all') @lang('main.companies')
            </a>

        </div>
    </div>


    <!-- Content Row -->
    <div class="row mb-3">
        <div class="col-xl-12 col-md-12 col-sm-12 mb-3">
            <div id="bs-stepper" class="bs-stepper">

                {{-- Setps --}}
                <div class="bs-stepper-header" role="tablist">

                    {{-- Step One Basic Info --}}
                    <div class="step" data-target="#basic-info">
                        <button type="button" class="step-trigger" role="tab" aria-controls="basic-info"
                            id="basic-info-trigger">
                            <span class="bs-stepper-circle">
                                <i class="fas fa-info"></i>
                            </span>
                            <span class="bs-stepper-label">
                                @lang('main.basic')
                            </span>
                        </button>
                    </div>

                    <div class="line"></div>

                    {{-- Step Two Prices and classifications Info --}}
                    <div class="step" data-target="#prices-and-classifications-info">
                        <button type="button" class="step-trigger" role="tab"
                            aria-controls="prices-and-classifications-info" id="prices-and-classifications-info-trigger">
                            <span class="bs-stepper-circle">
                                <i class="fas fa-business-time"></i>
                            </span>
                            <span class="bs-stepper-label">
                                @lang('main.work_times')
                            </span>
                        </button>
                    </div>

                </div>
                {{-- Steps Content --}}
                <div class="bs-stepper-content">
                    <div class="card">
                        <div class="card-body border-left-info">
                            <div class="card-title font-weight-bold h5 text-center text-info">{{ $sectionName }}</div>
                            <div class="card-text">
                                <form id="update-form" action="{{ route('dashboard.companies.update', $company) }}"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PATCH')


                                    {{-- Basic Info Step Content --}}
                                    <div id="basic-info" class="content fade" role="tabpanel"
                                        aria-labelledby="basic-info-trigger">

                                        <div class="card mb-3">
                                            <div class="card-body border-left-info">

                                                <div class="card-text">
                                                    @include('dashboard.companies.includes.edit._basic_info')
                                                    <hr>
                                                    @include('dashboard.companies.includes.edit._company_name')
                                                    <hr>
                                                    @include('dashboard.companies.includes.edit._categorizations_info')
                                                    <hr>
                                                    @include('dashboard.companies.includes.edit._status_info')
                                                    <hr>
                                                    @include('dashboard.companies.includes.edit._location_info')
                                                    <hr>
                                                    @include('dashboard.companies.includes.create._countries')
                                                    <hr>
                                                    @include('dashboard.companies.includes.edit._files_info')
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-6 col-sm-12 mb-xl-0 mb-lg-0 mb-md-0 mb-3 text-align-start">
                                            </div>
                                            <div class="col-md-6 col-sm-12 text-align-end text">
                                                <button type="button"
                                                    class="btn btn-primary btn-next mx-1">@lang('main.next')</button>
                                            </div>

                                            <div class="col-md-6 col-sm-12 mb-xl-0 mb-lg-0 mb-md-0 mb-3 text-align-start">
                                                <button type="submit" class="btn btn-info mx-1">@lang('main.edit')
                                                    @lang('main.company')</button>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Prices and classification Info Step Content --}}
                                    <div id="prices-and-classifications-info" class="content fade" role="tabpanel"
                                        aria-labelledby="prices-and-classifications-info-trigger">


                                        @include('dashboard.companies.includes.edit._work_times_info')


                                        <hr>
                                        <div class="form-group row">

                                            <div class="col-md-6 col-sm-12 mb-xl-0 mb-lg-0 mb-md-0 mb-3 text-align-start">
                                            </div>
                                            <div class="col-md-6 col-sm-12 text-align-end text">
                                                <button type="button"
                                                    class="btn btn-warning btn-previous mb-xl-0 mb-lg-0 mb-md-0 mb-1 mx-1">@lang('main.previous')</button>

                                            </div>

                                            <div class="col-md-6 col-sm-12 mb-xl-0 mb-lg-0 mb-md-0 mb-3 text-align-start">
                                                <button type="submit" class="btn btn-info mx-1">@lang('main.edit')
                                                    @lang('main.company')</button>
                                            </div>


                                        </div>
                                    </div>

                                </form>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

        </div>


        @php
            $lat = $company->latitude;
            $long = $company->longitude;
        @endphp
    @endsection

    @section('scripts')
        {{-- Bootstrap Stepper --}}
        <script src="{{ asset('/dashboard_assets/libs/bs-stepper/js/bs-stepper.min.js') }}" type="text/javascript"></script>


        <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>

        {{-- Gijgo Picker --}}
        <script src="{{ asset('/dashboard_assets/libs/gijgo-bootstrap-pickers/js/gijgo.min.js') }}" type="text/javascript">
        </script>


        {{-- Jquery Validate --}}
        <script src="{{ asset('/dashboard_assets/libs/jquery-validate/jquery-validate.min.js') }}" type="text/javascript">
        </script>

        {{-- Bootstrap Switch --}}
        <script src="{{ asset('/dashboard_assets/libs/bootstrap-switch/js/bootstrap-switch.js') }}" type="text/javascript">
        </script>


        {{-- Bootstrap file input --}}
        <script src="{{ asset('/dashboard_assets/libs/bootstrap-fileinput/bootstrap-fileinput.js') }}" type="text/javascript">
        </script>

        {{-- Google Apis --}}
        <script type="text/javascript"
            src="https://maps.googleapis.com/maps/api/js?key={{ config('defaults.system.google_map_key') }}&libraries=places">
        </script>


        <script>
            $(document).ready(function() {
                var validator = $('#update-form').validate();
                var form = document.getElementById('update-form');
                var stepperEl = document.getElementById('bs-stepper');
                var stepper = new Stepper(stepperEl, {
                    linear: false,
                    animation: true
                });

                $('.btn-next').on('click', function(e) {
                    e.preventDefault();

                    stepper.next();
                });

                $('.btn-previous').on('click', function(e) {
                    e.preventDefault();

                    stepper.previous();
                });

                /*      stepper.to(2); */

                $('[type=submit]').on('click', function(e) {
                    if (!validator.form()) {
                        /* View HTML Native Errors */
                        form.reportValidity();
                        event.preventDefault();
                    }
                });


                stepperEl.addEventListener('show.bs-stepper', function(event) {
                    /* var previousStep    = event.detail.from; */

                    if (!validator.form()) {
                        /* View HTML Native Errors */
                        form.reportValidity();
                        event.preventDefault();
                    }
                });

            });
        </script>

        <script>
            $(document).ready(function() {

                var geo = new google.maps.LatLng("{{ $lat }}", "{{ $long }}");

                updateMap({
                    lat: geo.lat(),
                    lng: geo.lng()
                }, 15);
            });

            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 5,
            });

            var marker = new google.maps.Marker({
                animation: google.maps.Animation.DROP,
                map: map,
                draggable: true,
            });

            map.addListener('click', function(event) {
                marker.setPosition({
                    lat: event.latLng.lat(),
                    lng: event.latLng.lng()
                });
            });

            map.addListener('zoom_changed', function() {
                $('#zoom').val(map.getZoom());
            });

            marker.addListener('position_changed', function() {


                updateGeo(marker.getPosition().lng(), marker.getPosition().lat());
            });

            var address = new google.maps.places.Autocomplete(document.getElementById('address'), {});

            address.addListener('place_changed', function() {
                var place = address.getPlace();
                var street = place.address_components[2].long_name;
                var area = place.address_components[3].long_name;
                var city = place.address_components[4].long_name;
                var country = place.address_components[5].long_name;
                var country_code = place.address_components[5].short_name;
                var full_address = place.name + ' , ' + street + ' , ' + area + ' , ' + city + ' , ' + country;


                updateMap({
                    lat: place.geometry.location.lat(),
                    lng: place.geometry.location.lng()
                });

                updateAddressInfo(
                    city,
                    country,
                    country_code,
                    area
                );
            });

            function updateMap(geoLocation, zoom = 15) {
                map.setCenter(geoLocation);
                marker.setPosition(geoLocation);
                map.setZoom(zoom);
            }

            function updateGeo(longitude, latitude) {
                $('#longitude').val(longitude);
                $('#latitude').val(latitude);
            }

            function updateAddressInfo(city, country, country_code) {

                $('#city').val(city);
                $('#country').val(country);
                $('#country_code').val(country_code);
                $('#area').val(area);
            }
        </script>
    @endsection
