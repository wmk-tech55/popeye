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
            <a href="{{ route('dashboard.promoCodes.show', $promoCode) }}" class="d-sm-inline-block btn btn-sm btn-info shadow-sm">
                <i class="fas fa-eye fa-sm text-white-50"></i> 
                @lang('main.show') {{ $promoCode->code }}
            </a>
            
            <a href="{{ route('dashboard.promoCodes.index') }}" class="d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-store fa-sm text-white-50"></i> 
                @lang('main.show_all') @lang('main.promoCodes')
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
                        <form action="{{ route('dashboard.promoCodes.update', $promoCode) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
    
                             
                            @include('dashboard.promoCodes.includes.edit._basic_info')
                              
  
                            <div class="form-group row">
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-info">@lang('main.edit') @lang('main.promo_code')</button>
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
   
 <script>
     $('#expiry_date').datepicker({
         format: 'yyyy-mm-dd',
         autoclose: true,
         startDate: '{{ today()->toDateString() }}',
         clearBtn: true,
         rtl: '{{ isRtl() }}',
         todayHighlight: true,
      });
 </script>
 
@endsection