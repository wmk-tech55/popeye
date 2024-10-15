@extends('layouts.app')

@section('content')
    @php
        $settings = getSettings();
    @endphp

    <main id="main">
        <section class="breadcrumbs">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>@lang('site.privacy_policy')</h2>
                    <ol>
                        <li><a href="/">@lang('site.home')</a></li>
                        <li>@lang('site.privacy_policy')</li>
                    </ol>
                </div>
            </div>
        </section>
        <section class="inner-page pt-4">
            <div class="container">
                <p>
                    {!! $settings->privacy_policy_by_lang !!}
                </p>
            </div>
        </section>
    </main>
    <!-- End #main -->
@endsection
