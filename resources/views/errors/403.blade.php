@extends('layouts.app')

@section('content')
 

<main id="main">
    <section class="breadcrumbs">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <h2>403</h2>
                <ol>
                    <li><a href="/">@lang('site.home')</a></li>
                    <li>403</li>
                </ol>
            </div>
        </div>
    </section>
    <section class="text-center pt-4">
        <div class="container">
            <h1> 403</h1>
            <h2> {{ __($exception->getMessage() ?: 'Forbidden') }}</h2>
        </div>
    </section>
</main>
<!-- End #main -->
@endsection