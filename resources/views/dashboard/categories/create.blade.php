@extends('dashboard.layouts.app')

@section('section', $sectionName)

@section('styles')
    {{-- Bootstrap file input --}}
    <link href="{{ asset('/dashboard_assets/libs/bootstrap-fileinput/bootstrap-fileinput.css') }}" rel="stylesheet"
        type="text/css" />

    {{-- Bootstrap Switch --}}
    <link href="{{ asset('/dashboard_assets/libs/bootstrap-switch/css/bootstrap-switch' . getRtlDirection() . '.min.css') }}"
        rel="stylesheet" type="text/css" />
 
@endsection

@section('content')

    {{-- <div class="alert alert-info">
    <i class="fas fa-exclamation-circle"></i>
    @lang('main.visit_website_to_download_icons') : 
  
    <a target="_new" href="https://www.flaticon.com/">flaticon</a>
</div> --}}

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $sectionName }}</h1>
        <a href="{{ route('dashboard.categories.index') }}" class="d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-tag fa-sm text-white-50"></i>
            @lang('main.show_all') @lang('main.categories')
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

                        <form action="{{ route('dashboard.categories.store') }}" method="POST"
                            enctype="multipart/form-data">

                            @csrf

                            {{-- EN Name --}}
                            <div class="form-group my-4 row">
                                <label class="col-sm-3 col-form-label" for="en_name">@lang('main.en_name')<span
                                        class="required"></span> </label>
                                <div class="col-sm-8">
                                    <input type="text" name="en_name" value="{{ old('en_name') }}" id="en_name"
                                        class="form-control @error('en_name') is-invalid @enderror"
                                        placeholder="@lang('main.en_name')" required>
                                    @error('en_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- AR Name --}}
                            <div class="form-group mb-4 row">
                                <label class="col-sm-3 col-form-label" for="ar_name">@lang('main.ar_name')<span
                                        class="required"></span> </label>
                                <div class="col-sm-9">
                                    <input type="text" name="ar_name" value="{{ old('ar_name') }}" id="ar_name"
                                        class="form-control @error('ar_name') is-invalid @enderror"
                                        placeholder="@lang('main.ar_name')" required>
                                    @error('ar_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- store categorizations --}}
                            <div class="form-group mb-4 row">
                                <label class="col-sm-3 col-form-label" for="categorization_id">@lang('main.store_categorizations')<span
                                        class="required"></span> </label>
                                <div class="col-sm-9">
                                    <select name="categorization_id" id="categorization_id"
                                        class="form-control select2  @error('categorization_id') is-invalid @enderror"
                                        data-placeholder="@lang('main.store_categorizations')">
                                        <option disabled selected>@lang('main.categorization')</option>
                                        @foreach ($categorizations as $categorization)
                                            <option value="{{ $categorization->id }}"
                                                {{ $categorization->id == old('categorization_id') ? 'selected' : '' }}>
                                                {{ $categorization->name_by_lang }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('categorization_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

 
                            {{-- Status --}}
                            <div class="form-group mb-4 row">
                                <label class="col-sm-2 col-form-label" for="status">@lang('main.status')<span
                                        class="required"></span> </label>
                                <div class="col-sm-10">
                                    <input type="hidden" name="status" value="disable">

                                    <input type="checkbox" id="status" name="status" class="switch"
                                        data-on-color="success" data-off-color="danger" data-on-text="@lang('main.active')"
                                        data-off-text="@lang('main.disabled')" value="active">

                                    @error('status')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Icon --}}
                            <div class="form-group mb-4 row">
                                <label class="col-sm-2 col-form-label" for="icon">@lang('main.icon')<span
                                        class="required"></span> </label>
                                <div class="col-sm-10">

                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput">

                                        </div>
                                        <div>
                                            <span class="btn blue btn-outline btn-file">
                                                <span class="fileinput-new btn btn-success"> @lang('main.select_image')
                                                </span>
                                                <span class="fileinput-exists btn btn-info"> @lang('main.change') </span>
                                                <input type="file" name="icon" id="icon" accept=".jpg,.png,.jpeg"
                                                    required>
                                            </span>
                                            <a href="javascript:;" class="btn btn-danger fileinput-exists"
                                                data-dismiss="fileinput"> @lang('main.remove') </a>
                                        </div>
                                    </div>



                                    @error('icon')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-info">@lang('main.add')
                                        @lang('main.category')</button>
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
    {{-- Bootstrap file input --}}
    <script src="{{ asset('/dashboard_assets/libs/bootstrap-fileinput/bootstrap-fileinput.js') }}" type="text/javascript">
    </script>

    {{-- Bootstrap Switch --}}
    <script src="{{ asset('/dashboard_assets/libs/bootstrap-switch/js/bootstrap-switch.js') }}" type="text/javascript">
    </script>

    {{-- Select2 --}}
    

@endsection
