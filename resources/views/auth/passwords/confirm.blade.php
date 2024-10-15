@extends('layouts.app')

@section('content')

<section id="hero" class="d-flex align-items-center">
    <div class="container">
        <div class="row">
            <div class=" mt-4 col-lg-6 d-lg-flex flex-lg-column justify-content-center align-items-stretch pt-5 pt-lg-0 order-2 order-lg-1"
                data-aos="fade-up">
                <div class="mt-4">

                    <h4>{{ __('Confirm Password') }}</h4>
                    <h46> {{ __('Please confirm your password before continuing.') }}</h6>


                        <form class="form" action="{{ route('login') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label>@lang('main.password')</label>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    required autocomplete="current-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>


                            <button type="submit" class="download-btn btn login-btn-padding">
                                {{ __('Confirm Password') }}

                            </button>

                            @if (Route::has('password.request'))
                            <a class="download-btn btn login-btn-padding" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                            @endif

                        </form>

                </div>
            </div>
        </div>
    </div>


</section>
@endsection