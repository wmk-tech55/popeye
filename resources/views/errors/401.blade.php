@extends('layouts.app')

@section('content')
 

<main id="main">
    <section class="breadcrumbs">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <h2>401</h2>
                <ol>
                    <li><a href="/">@lang('site.home')</a></li>
                    <li>401</li>
                </ol>
            </div>
        </div>
    </section>
    <section class="text-center pt-4">
        <div class="container">
            <h1> 401</h1>
            <h2> {{ __('Unauthorized') }}</h2>
        </div>
    </section>
</main>
<!-- End #main -->
@endsection