@extends('dashboard.layouts.app')

@section('section', $sectionName)

@section('styles')

    {{-- Bootstrap Stepper --}}
    <link href="{{ asset('/dashboard_assets/libs/bs-stepper/css/bs-stepper.min.css') }}" rel="stylesheet" type="text/css" />
 

    {{-- Bootstrap Switch --}}
    <link href="{{ asset('/dashboard_assets/libs/bootstrap-switch/css/bootstrap-switch' . getRtlDirection() . '.min.css') }}"
        rel="stylesheet" type="text/css" />
@endsection

@section('content')

    {{-- <div class="alert alert-info">
    <i class="fas fa-exclamation-circle"></i>
    @lang('main.default_price_ratio_message')
</div> --}}

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $sectionName }}</h1>

        <div>
            <a href="{{ route('dashboard.products.show', $product) }}"
                class="d-sm-inline-block btn btn-sm btn-info shadow-sm">
                <i class="fas fa-eye fa-sm text-white-50"></i>
                @lang('main.show') {{ $product->name_by_lang }}
            </a>

            <a href="{{ route('dashboard.products.index') }}" class="d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plane fa-sm text-white-50"></i>
                @lang('main.show_all') @lang('main.products')
            </a>

        </div>
    </div>


    <!-- Content Row -->
    <div class="row mb-3">
        <div class="col-xl-12 col-md-12 col-sm-12 mb-3">
            <div id="bs-stepper" class="bs-stepper">

                {{-- Setps --}}
                <div class="bs-stepper-header" role="tablist">

                    {{-- Step One Basic Info --}}
                    <div class="step" data-target="#basic-info">
                        <button type="button" class="step-trigger" role="tab" aria-controls="basic-info"
                            id="basic-info-trigger">
                            <span class="bs-stepper-circle">
                                <i class="fas fa-info"></i>
                            </span>
                            <span class="bs-stepper-label">
                                @lang('main.basic')
                            </span>
                        </button>
                    </div>

                    <div class="line"></div>

                    {{-- Step Two Prices and classifications Info --}}
                    <div class="step" data-target="#prices-and-classifications-info">
                        <button type="button" class="step-trigger" role="tab"
                            aria-controls="prices-and-classifications-info" id="prices-and-classifications-info-trigger">
                            <span class="bs-stepper-circle">
                                <i class="fas fa-plus"></i>
                            </span>
                            <span class="bs-stepper-label">
                                @lang('main.additions')
                            </span>
                        </button>
                    </div>

                </div>
                {{-- Steps Content --}}
                <div class="bs-stepper-content">
                    <div class="card">
                        <div class="card-body border-left-info">
                            <div class="card-title font-weight-bold h5 text-center text-info">{{ $sectionName }}</div>
                            <div class="card-text">
                                <form id="update-form" action="{{ route('dashboard.products.update', $product) }}"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PATCH')


                                    {{-- Basic Info Step Content --}}
                                    <div id="basic-info" class="content fade" role="tabpanel"
                                        aria-labelledby="basic-info-trigger">

                                        @include('dashboard.products.includes.edit._basic_info')
                                        <hr>
                                        @include('dashboard.products.includes.edit._categorization_info')
                                        <hr>
                                        @include('dashboard.products.includes.edit._categories_info')
                                        <hr>
                                        @include('dashboard.products.includes.edit._status')
                                        <hr>
                                        @include('dashboard.products.includes.edit._media')
                                        <hr>

                                        <div class="form-group row">
                                            <div class="col-md-6 col-sm-12 mb-xl-0 mb-lg-0 mb-md-0 mb-3 text-align-start">
                                            </div>
                                            <div class="col-md-6 col-sm-12 text-align-end text">
                                                <button type="button"
                                                    class="btn btn-primary btn-next mx-1">@lang('main.next')</button>
                                            </div>

                                            <div class="col-md-6 col-sm-12 mb-xl-0 mb-lg-0 mb-md-0 mb-3 text-align-start">
                                                <button type="submit" class="btn btn-info mx-1">@lang('main.edit')
                                                    @lang('main.product')</button>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Prices and classification Info Step Content --}}
                                    <div id="prices-and-classifications-info" class="content fade" role="tabpanel"
                                        aria-labelledby="prices-and-classifications-info-trigger">


                                        @include('dashboard.products.includes.edit._additions_info')

                                        <hr>
                                        <div class="form-group row">

                                            <div class="col-md-6 col-sm-12 mb-xl-0 mb-lg-0 mb-md-0 mb-3 text-align-start">
                                            </div>
                                            <div class="col-md-6 col-sm-12 text-align-end text">
                                                <button type="button"
                                                    class="btn btn-warning btn-previous mb-xl-0 mb-lg-0 mb-md-0 mb-1 mx-1">@lang('main.previous')</button>

                                            </div>

                                            <div class="col-md-6 col-sm-12 mb-xl-0 mb-lg-0 mb-md-0 mb-3 text-align-start">
                                                <button type="submit" class="btn btn-info mx-1">@lang('main.edit')
                                                    @lang('main.product')</button>
                                            </div>


                                        </div>
                                    </div>

                                </form>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

        </div>
        @include('dashboard.products.includes.model._group_types_model')

    @endsection

    @section('scripts')
        {{-- Bootstrap Stepper --}}
        <script src="{{ asset('/dashboard_assets/libs/bs-stepper/js/bs-stepper.min.js') }}" type="text/javascript"></script>
 
        {{-- CKeditor --}}
        <script src="{{ asset('/dashboard_assets/libs/ckeditor/ckeditor.js') }}" type="text/javascript"></script>


        {{-- Jquery Validate --}}
        <script src="{{ asset('/dashboard_assets/libs/jquery-validate/jquery-validate.min.js') }}" type="text/javascript">
        </script>

        {{-- Bootstrap Switch --}}
        <script src="{{ asset('/dashboard_assets/libs/bootstrap-switch/js/bootstrap-switch.js') }}" type="text/javascript">
        </script>

        <script>
            $(document).ready(function() {
                var validator = $('#update-form').validate();
                var form = document.getElementById('update-form');
                var stepperEl = document.getElementById('bs-stepper');
                var stepper = new Stepper(stepperEl, {
                    linear: false,
                    animation: true
                });

                $('.btn-next').on('click', function(e) {
                    e.preventDefault();

                    stepper.next();
                });

                $('.btn-previous').on('click', function(e) {
                    e.preventDefault();

                    stepper.previous();
                });

                /*      stepper.to(2); */

                $('[type=submit]').on('click', function(e) {
                    if (!validator.form()) {
                        /* View HTML Native Errors */
                        form.reportValidity();
                        event.preventDefault();
                    }
                });


                stepperEl.addEventListener('show.bs-stepper', function(event) {
                    /* var previousStep    = event.detail.from; */

                    if (!validator.form()) {
                        /* View HTML Native Errors */
                        form.reportValidity();
                        event.preventDefault();
                    }
                });

            });
        </script>

        {{-- Delete Media --}}
        <script>
            deleteMedia('.delete-image', '{{ route('dashboard.products.media.destroy', $product) }}');
        </script>

        <script>
            $(document).on('change', '.categorizations_id', function() {
                var categorization_id = $(this).find('option:selected').val();

                var classification_container = $(this)
                    .parent()
                    .parent()
                    .next();


                $.ajax({
                    url: "{{ route('dashboard.classifications.get_by_categorization') }}",
                    data: {
                        categorization_id: categorization_id
                    }
                }).done(function(data) {

                    classification_container.html(data);

                    var classification = classification_container
                        .children()
                        .children()
                        .children('select');

                    classification.select2({
                        theme: 'bootstrap'
                    });
                });
            });
        </script>



        <script>
            $(document).ready(function() {
                $('#categorization_id').trigger('change');
                $('input[name=has_additions]:checked').trigger("change");
            });
        </script>

        <script>
            $('input:radio[name="has_additions"]').on('change', function() {
                var option = $(this).val();

                if (option === 'yes') {
                    $('.additions-options-section').removeClass('d-none');

                } else {

                    $('.additions-options-section').addClass('d-none');
                }

            });
        </script>



        <!--------------- add new additions group to product------------->
        <script type="text/javascript">
            $(document).ready(function() {
                $("#newAdditionGroupRow").click(function() {
                    var number = $('[data-id=other-additions-groups]').length;
                    $.ajax({
                        url: "{{ route('dashboard.products.addition_group_row') }}",
                        type: "GET",
                        data: {
                            number
                        },
                    }).done(function(data) {
                        $('.group-type').select2('destroy');
                        $('#newAdditionGroupRowContainer').append(data);
                        $('.group-type').select2({
                            theme: 'bootstrap'
                        });
                    });
                });
            });

            $(document).on('click', '#removeAdditionsGroupRow', function() {
                $(this).closest('#accordionExample').remove();
            });
        </script>

        <!--------------- add new additions to product------------->
        <script type="text/javascript">
            $(document).on('click', '#newAdditionRow', function() {

                var index = $(this).data('addition-index');
                var number = $('[data-id=other-additions-groups]').length;

                var section = $(this).parent().children('#newAdditionRowContainer');
                $.ajax({
                    url: "{{ route('dashboard.products.addition_row') }}",
                    type: "GET",
                    data: {
                        number,
                        index
                    },
                }).done(function(data) {

                    section.append(data)

                });


            });

            $(document).on('click', '#removeAdditionsRow', function() {
                $(this).closest('.additions-container').remove();
            });
        </script>

        <script>
            $('#categorizationId').on('change', function() {
                var categorizationId = $(this).find('option:selected').val();

                $.ajax({
                    url: "{{ route('dashboard.categories.get_by_categorization') }}",
                    data: {
                        categorization_id: categorizationId
                    }
                }).done(function(data) {
                    $('#categorizationId').select2('destroy');
                    $('#categoryId').html(data);
                    $('#categorizationId').select2({
                        theme: 'bootstrap'
                    });
                });

            });
        </script>
    @endsection
