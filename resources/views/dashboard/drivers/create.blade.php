@extends('dashboard.layouts.app')

@section('section', $sectionName)

@section('styles')
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
 
@endsection

@section('content')

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $sectionName }}</h1>
        <a href="{{ route('dashboard.drivers.index') }}" class="d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-drivers fa-sm text-white-50"></i>
            @lang('main.show_all') @lang('main.drivers')
        </a>
    </div>

    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-1 col-md-1"></div>
        <div class="col-xl-12 col-md-12 col-sm-12">
            <form action="{{ route('dashboard.drivers.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="card mb-3">
                    <div class="card-body border-left-info">

                        <div class="card-title font-weight-bold h5 text-center text-info">{{ $sectionName }}</div>

                        <div class="card-title font-weight-bold h4 text-muted">@lang('main.basic')</div>
                        <div class="card-text">
                            @include('dashboard.drivers.includes.create._basic_info')
                        </div>
                    </div>
                </div>

                <div class="card mb-3  ">
                    <div class="card-body border-left-primary">
                        <div class="card-title font-weight-bold h4 text-muted">@lang('main.location_info')</div>

                        <div class="card-text">
                            @include('dashboard.drivers.includes.create._location_info')
                            <hr>
                            @include('dashboard.drivers.includes.create._countries')
                            <hr>
                        </div>
                    </div>
                </div>

                <div class="card mb-3  ">
                    <div class="card-body border-left-primary">
                        <div class="card-title font-weight-bold h4 text-muted">@lang('main.files')</div>

                        <div class="card-text">
                            

                            @include('dashboard.drivers.includes.create._files_info')
                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-body border-left-info">
                        <div class="form-group row">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-info">@lang('main.add') @lang('main.driver')</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>


@endsection

@section('scripts')

    {{-- Google Apis --}}
    <script type="text/javascript"
        src="https://maps.googleapis.com/maps/api/js?key={{config('defaults.system.google_map_key')}}&libraries=places"></script>

    {{-- Bootstrap file input --}}
    <script src="{{ asset('/dashboard_assets/libs/bootstrap-fileinput/bootstrap-fileinput.js') }}" type="text/javascript">
    </script>

     
    {{-- Google AutoComplete Setup --}}

    <script>
        $(document).ready(function()
         {

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var lat = position.coords.latitude;
                    var lng = position.coords.longitude;

                    updateMap({
                        lat: lat,
                        lng: lng
                    }, 15);
                });
            }
        }
        );

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

        function updateAddressInfo(city, country, country_code, area) {

            $('#city').val(city);
            $('#country').val(country);
            $('#country_code').val(country_code);
            $('#area').val(area);
        }
    </script>

@endsection
