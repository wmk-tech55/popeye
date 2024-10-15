@extends('layouts.app')

@section('content')
    @php
        $settings = getSettings();
    @endphp

    <!-- ======= Intro Section ======= -->
    <section id="intro" class="clearfix">
        <div class="container" data-aos="fade-up">

            <div class="intro-img" data-aos="zoom-out" data-aos-delay="200">
                <img src="{{ asset('assets/img/popeye-logo-large.png') }}" alt="" class="img-fluid">
            </div>

            <div class="intro-info" data-aos="zoom-in" data-aos-delay="100">
                <h2> @lang('site.download_our_apps') </h2>
                <div>
                    @if ($settings->ios)
                        <a href="{{ $settings->ios }}" class="btn-get-started scrollto">
                            <i class="bx bxl-apple"></i>
                            @lang('site.app_store')
                        </a>
                    @endif

                    @if ($settings->android)
                        <a href="{{ $settings->android }}" class="btn-get-started scrollto">
                            <i class="bx bxl-play-store"></i>
                            @lang('site.google_play')
                        </a>
                    @endif

                </div>
            </div>

        </div>
    </section>
    <!-- End Intro Section -->
@endsection
