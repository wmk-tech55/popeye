@extends('dashboard.layouts.app') 

@section('section', $sectionName)

@section('styles')
    {{-- Bootstrap Datepicker --}}
    <link href="{{ asset('/dashboard_assets/libs/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />

    {{-- gijgo-bootstrap-pickers --}}
    <link href="{{ asset('/dashboard_assets/libs/gijgo-bootstrap-pickers/css/gijgo.min.css') }}" rel="stylesheet" type="text/css" />

     
    <style>
        .grid-col {
            flex: 1;
            padding: 0 .1em;
        }
    </style>

    
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
        
        <div>
            <a href="{{ route('dashboard.discounts.show', $discount) }}" class="d-sm-inline-block btn btn-sm btn-info shadow-sm">
                <i class="fas fa-eye fa-sm text-white-50"></i> 
                @lang('main.show') {{ $discount->name_by_lang }}
            </a>
            
            <a href="{{ route('dashboard.discounts.index') }}" class="d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-store fa-sm text-white-50"></i> 
                @lang('main.show_all') @lang('main.discounts')
            </a>

        </div>
    </div>
    
    <!-- Content Row -->
    <div class="row mb-3">
    
        <div class="col-xl-12 col-md-12 col-sm-12 mb-3">
            <div class="card">
                <div class="card-body border-left-info">
                    <div class="card-title font-weight-bold h5 text-center text-info">{{ $sectionName }}</div>
                    <div class="card-text">
                        <form action="{{ route('dashboard.discounts.update', $discount) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
    
                             
                            @include('dashboard.discounts.includes.edit._basic_info')
                            
                            <hr>
                           
                            
                            @include('dashboard.discounts.includes.edit._categories_info')

                            <hr>
                         
                            
                            @include('dashboard.discounts.includes.edit._products_info')

                             
  
                            <div class="form-group row">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-info">@lang('main.edit') @lang('main.discount')</button>
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
        console.log(muliDatePicker);
        
        /* give $().bootstrapMuliDatePicke the bootstrap-datepicker functionality */
        $.fn.bootstrapMuliDatePicker = muliDatePicker;                 
    </script>

    {{-- gijgo-bootstrap-pickers --}}
    <script src="{{ asset('/dashboard_assets/libs/gijgo-bootstrap-pickers/js/gijgo.min.js') }}" type="text/javascript"></script>
        
    {{-- Colcade --}}
    <script src="{{ asset('/dashboard_assets/libs/colcade/colcade.js') }}" type="text/javascript"></script>
  
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

    {{-- Delete Media --}}
    <script>
        deleteMedia('.delete-image', '{{ route("dashboard.discounts.media.destroy", $discount) }}');
    </script>
@endsection