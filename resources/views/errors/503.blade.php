@extends('layouts.app')

@section('content')
 

<main id="main">
    <section class="breadcrumbs">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <h2>503</h2>
                <ol>
                    <li><a href="/">@lang('site.home')</a></li>
                    <li>503</li>
                </ol>
            </div>
        </div>
    </section>
    <section class="text-center pt-4">
        <div class="container">
            <h1> 503</h1>
            <h2> {{ __($exception->getMessage() ?: 'Service Unavailable') }}</h2>
        </div>
    </section>
</main>
<!-- End #main -->
@endsection