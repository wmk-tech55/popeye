@extends('layouts.app')

@section('content')
    @php
        $settings = getSettings();
    @endphp

    <main id="main">
        <section class="breadcrumbs">
            <div class="container">

                <div class="d-flex justify-content-between align-items-center">
                    <h2>@lang('site.terms_and_conditions')</h2>
                    <ol>
                        <li><a href="/">@lang('site.home')</a></li>
                        <li>@lang('site.terms_and_conditions')</li>
                    </ol>
                </div>
            </div>
        </section>
        <section class="inner-page pt-4 pb-4 mb-4">
            <div class="container">
              
                    {!! $settings->terms_and_conditions_by_lang !!}
               
            </div>
        </section>
    </main>
@endsection
