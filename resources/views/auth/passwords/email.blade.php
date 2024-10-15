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
                    <form class="form" action="{{ route('login') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label>@lang('main.email')</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" placeholder="@lang('site.email_address')" required autocomplete="email" autofocus>

                            @error('email')
                            <div class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                            @enderror
                        </div>


                        <button type="submit" class="download-btn btn login-btn-padding">
                            {{ __('Send Password Reset Link') }}

                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>


</section>
@endsection