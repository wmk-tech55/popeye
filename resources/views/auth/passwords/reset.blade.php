@extends('layouts.app')

@section('content')

<section id="hero" class="d-flex align-items-center">
    <div class="container">
        <div class="row">
            <div class=" mt-4 col-lg-6 d-lg-flex flex-lg-column justify-content-center align-items-stretch pt-5 pt-lg-0 order-2 order-lg-1"
                data-aos="fade-up">
                <div class="mt-4">

                    <h4>{{ __('Reset Password') }}</h4>
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                    <form method="POST" class="form" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <div class="form-group">
                            <label>@lang('main.email')</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ $email ?? old('email') }}" required autocomplete="email"
                                autofocus>
                            @error('email')
                            <div class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>@lang('main.password')</label>
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password" required
                                autocomplete="new-password">

                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>{{ __('Confirm Password') }}</label>
                            <input id="password-confirm" type="password" class="form-control"
                                name="password_confirmation" required autocomplete="new-password">

                            @error('password_confirmation')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>


                        <button type="submit" class="download-btn btn login-btn-padding">
                            {{ __('Reset Password') }}

                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>


</section>
@endsection