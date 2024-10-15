@extends('dashboard.layouts.app') 

@section('section', $sectionName)

@section('styles')
    {{-- Bootstrap Switch --}}
    <link href="{{ asset('/dashboard_assets/libs/bootstrap-switch/css/bootstrap-switch' . getRtlDirection() . '.min.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')

    <!-- Page Heading -->
    <div class="d-flex flex-column flex-lg-row align-items-center justify-content-between mb-4">

        <h1 class="h3 mb-3 text-gray-800">{{ $sectionName }}</h1>
        <a href="{{ route('dashboard.faqs.index') }}" class="d-sm-inline-block mb-2 btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-comments fa-sm text-white-50"></i> 
            @lang('main.show_all') @lang('main.faqs')
        </a>
    </div>
    
    <!-- Content Row -->
    <div class="row">
    
        <div class="col-xl-1 col-md-1"></div>
        <div class="col-xl-12 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body border-left-info">
                    <div class="card-title font-weight-bold h5 text-center text-info">{{ $sectionName }}</div>
                    <div class="card-text">

                        <form action="{{ route('dashboard.faqs.store') }}" method="POST">

                            @csrf
    
                            {{-- EN Question --}}
                            <div class="form-group my-4 row">
                                <label class="col-sm-2 col-form-label" for="en_question">@lang('main.en_question')<span class="required"></span> </label>
                                <div class="col-sm-10">
                                    <input type="text" name="en_question" value="{{ old('en_question') }}" id="en_question" class="form-control @error('en_question') is-invalid @enderror" placeholder="@lang('main.en_question')" required>
                                    @error('en_question')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            
                            {{-- AR Question --}}
                            <div class="form-group mb-4 row">
                                <label class="col-sm-2 col-form-label" for="ar_question">@lang('main.ar_question')<span class="required"></span> </label>
                                <div class="col-sm-10">
                                    <input type="text" name="ar_question" value="{{ old('ar_question') }}" id="ar_question" class="form-control @error('ar_question') is-invalid @enderror" placeholder="@lang('main.ar_question')" required>
                                    @error('ar_question')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- English Answer --}}
                            <div class="form-group mb-4 row">
                                <label class="col-sm-2 col-form-label" for="en_answer">@lang('main.en_answer')<span class="required"></span> </label>
                                <div class="col-sm-10">
                                    <textarea name="en_answer" id="en_answer" class="form-control @error('en_answer') is-invalid @enderror" placeholder="@lang('main.en_answer')" required>
                                        {{ old('en_answer') }}
                                    </textarea>
                                    @error('en_answer')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Arabic Answer --}}
                            <div class="form-group mb-4 row">
                                <label class="col-sm-2 col-form-label" for="ar_answer">@lang('main.ar_answer')<span class="required"></span> </label>
                                <div class="col-sm-10">
                                    <textarea name="ar_answer" id="ar_answer" class="form-control @error('ar_answer') is-invalid @enderror" placeholder="@lang('main.ar_answer')" required>
                                        {{ old('ar_answer') }}
                                    </textarea>
                                    @error('ar_answer')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Status --}}
                            <div class="form-group mb-4 row">
                                <label class="col-sm-2 col-form-label" for="status">@lang('main.status')<span class="required"></span> </label>
                                <div class="col-sm-10">
                                    <input type="hidden" name="status" value="disable">
                                    
                                    <input type="checkbox" id="status" name="status" class="switch"  
                                        data-on-color="success" 
                                        data-off-color="danger" 
                                        data-on-text="@lang('main.active')" 
                                        data-off-text="@lang('main.disabled')" 
                                        value="active">

                                    @error('status')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                                
                            <div class="form-group row">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-info">@lang('main.add') @lang('main.faqs')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    

                </div>
            </div>
        </div>
    
    </div>
    
    
@endsection


@section('scripts')
    {{-- Bootstrap Switch --}}
    <script src="{{ asset('/dashboard_assets/libs/bootstrap-switch/js/bootstrap-switch.js') }}" type="text/javascript"></script>

    {{-- CKeditor --}}
    <script src="{{ asset('/dashboard_assets/libs/ckeditor/ckeditor.js') }}" type="text/javascript"></script>
@endsection