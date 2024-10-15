@extends('dashboard.layouts.app') 

@section('section', $sectionName)

@section('styles')
    {{-- Bootstrap Datepicker --}}
    <link href="{{ asset('/dashboard_assets/libs/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />

    {{-- gijgo-bootstrap-pickers --}}
    <link href="{{ asset('/dashboard_assets/libs/gijgo-bootstrap-pickers/css/gijgo.min.css') }}" rel="stylesheet" type="text/css" />
 

    @if (isRtl())
        <style>
            .gj-picker-bootstrap div[role=navigator] {
                flex-direction: row-reverse !important;
            }
 

        </style>

      
    @endif
@endsection

@section('content')
 

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $sectionName }}</h1>
        <a href="{{ route('dashboard.discounts.index') }}" class="d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-store fa-sm text-white-50"></i> 
            @lang('main.show_all') @lang('main.discounts')
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

                        <form action="{{ route('dashboard.discounts.store') }}" method="POST" enctype="multipart/form-data">

                            @csrf
                            
                            @include('dashboard.discounts.includes.create._basic_info')
                            
                            <hr>
                           
                            
                            @include('dashboard.discounts.includes.create._categories_info')

                            <hr>
                           
                            
                            @include('dashboard.discounts.includes.create._products_info')

                             
  
                            <div class="form-group row">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-info">@lang('main.add') @lang('main.discount')</button>
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
    {{-- Bootstrap Datepicker --}}
    <script src="{{ asset('/dashboard_assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>

    {{-- 
        Renaming bootstrap-datepicker jQuery plugin 
        Because we use gijgo pickers in many places and this plugin for mulidate task only
    --}}
    <script>
        /* return $.fn.datepicker to previously assigned value */
        var muliDatePicker = $.fn.datepicker.noConflict();
        
        /* give $().bootstrapMuliDatePicke the bootstrap-datepicker functionality */
        $.fn.bootstrapMuliDatePicker = muliDatePicker;                 
    </script>

    {{-- gijgo-bootstrap-pickers --}}
    <script src="{{ asset('/dashboard_assets/libs/gijgo-bootstrap-pickers/js/gijgo.min.js') }}" type="text/javascript"></script>
   
    {{-- CKeditor --}}
    <script src="{{ asset('/dashboard_assets/libs/ckeditor/ckeditor.js') }}" type="text/javascript"></script>

    {{-- multidate: 3, if pass number will limit selection to inserted number --}}
    <script>
        $('#category_parent_id').on('change', function () {
            var category_parent_id = $(this).find('option:selected').val();

            $.ajax({
                url: "{{ route('dashboard.categories.get_categories_by_parent_id') }}",
                data: {category_parent_id: category_parent_id}
            }).done(function (data) {   
                $('#category_parent_id').select2('destroy');
                $('.category_parent_container').html(data);
                $('#category_parent_id').select2({ theme: 'bootstrap' });
            });
            
        });
    </script>


<script>
    $('#categories_id').on('change', function () {
        var category_id = $(this).find('option:selected').val();

        $.ajax({
            url: "{{ route('dashboard.products.get_products_by_category_id') }}",
            data: {category_id: category_id}
        }).done(function (data) {   
            $('#categories_id').select2('destroy');
            $('.products_container').html(data);
            $('#categories_id').select2({ theme: 'bootstrap' });
        });
        
    });
</script>
@endsection