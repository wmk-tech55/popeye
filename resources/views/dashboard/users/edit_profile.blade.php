@extends('dashboard.layouts.app')

@section('section', $sectionName)

@section('styles')
    {{-- Bootstrap file input --}}
    <link href="{{ asset('/dashboard_assets/libs/bootstrap-fileinput/bootstrap-fileinput.css') }}" rel="stylesheet"
        type="text/css" />
 

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
@endsection

@section('content')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">@lang('main.edit') @lang('main.edit_profile')</h1>

    </div>

    <!-- Content Row -->
    <div class="row mb-3">
        <div class="col-xl-12 col-md-12 col-sm-12">
            <form action="{{ route('dashboard.users.company.update.profile') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <div class="card mb-3">
                    <div class="card-body border-left-info">

                        <div class="card-title font-weight-bold h5 text-center text-info">{{ $sectionName }}</div>

                        <div class="card-title font-weight-bold h4 text-muted">@lang('main.basic')</div>
                        <div class="card-text">
                            @include('dashboard.companies.includes.edit._basic_info')

                            @if (auth()->user()->isCompany())
                                <br>
                                @include('dashboard.companies.includes.edit._company_name')
                                <br>
                                @include('dashboard.companies.includes.edit._categorizations_info')
                                <br>
                                @include('dashboard.companies.includes.edit._location_info')
                                <hr>
                                @include('dashboard.companies.includes.edit._files_info')
                            @endif

                            <div class="form-group row">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-info">@lang('main.edit')</button>
                                </div>
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

                    <form action="{{ route('dashboard.users.update.password', $company) }}" method="POST">
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
                                <button type="submit" class="btn btn-danger">@lang('main.edit') @lang('main.password')</button>
                            </div>
                        </div>
                    </form>

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
    {{-- Bootstrap file input --}}
    <script src="{{ asset('/dashboard_assets/libs/bootstrap-fileinput/bootstrap-fileinput.js') }}" type="text/javascript">
    </script>
 
    {{-- Google Apis --}}
    <script type="text/javascript"
        src="https://maps.googleapis.com/maps/api/js?key={{config('defaults.system.google_map_key')}}&libraries=places"></script>

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

            updateMap({
                lat: place.geometry.location.lat(),
                lng: place.geometry.location.lng()
            });
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
    </script>

    <script>
        $(document).ready(function() {
            $('#type').trigger('change');
        });

        $('#type').on('change', function() {
            var option = $(this).find('option:selected').val();

            var companyData = $('.company-info');
            var companyOnlyData = $('.company-only');
            var tourGuideOnlyData = $('.tour-guide-only');

            if (option === 'company' || option === 'tour_guide') {
                if (option === 'company') {
                    companyOnlyData.removeClass('d-none');
                } else {
                    companyOnlyData.addClass('d-none');
                }

                if (option === 'tour_guide') {
                    tourGuideOnlyData.removeClass('d-none');
                } else {
                    tourGuideOnlyData.addClass('d-none');
                }

                companyData.removeClass('d-none');
            } else {
                companyData.addClass('d-none');
                companyOnlyData.addClass('d-none');
                tourGuideOnlyData.addClass('d-none');
            }

        });
    </script>
@endsection
