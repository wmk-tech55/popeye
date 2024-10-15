@extends('layouts.app')

@section('styles')
    <style>
        #footer {
            background: #03071E;
            padding: 0 0 30px 0;
            color: #eee;
            font-size: 14px;
            position: absolute !important;
            bottom: 0px !important;
            left: 0px !important;
            right: 0px !important;
        }
    </style>
@endsection

@section('content')
    <main id="main">
        <section class="breadcrumbs">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>@lang('site.login')</h2>
                    <ol>
                        <li><a href="/">@lang('site.home')</a></li>
                        <li>@lang('site.login')</li>
                    </ol>
                </div>
            </div>
        </section>

        <section class="portfolio-details">
            <div class="container">

                <div class="portfolio-description row">

                    <div class="col-lg-3">
                    </div>
                    <div class="col-lg-6">

                        <form class="mb-3" action="{{ route('login') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label>@lang('main.email')</label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    placeholder="@lang('main.email')" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>@lang('main.password')</label>
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror" id="password"
                                    placeholder="@lang('main.password')" required>
                                @error('password')
                                    <div class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary login-custom-btn">
                                    @lang('main.login')

                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-3">
                    </div>
                </div>
            </div>
        </section>



    </main>
    <!-- End #main -->
@endsection
