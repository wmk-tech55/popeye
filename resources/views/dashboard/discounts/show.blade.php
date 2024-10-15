@extends('dashboard.layouts.app')

@section('section', $sectionName)

@section('styles')
    <style>
        .grid-col {
            flex: 1;
            padding: 0 .1em;
        }
    </style>
@endsection

@section('content')
 
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{ $sectionName }}</h1>
    <div>
        @admin
        @if ($discount->isPublished())
            <a href="{{ route('dashboard.discounts.mark_as.pending', $discount) }}"
                class="d-sm-inline-block btn btn-sm btn-warning shadow-sm">
                <i class="fas fa-clock fa-sm text-white-50"></i>
                @lang('main.pending')
            </a>
        @else
            <a href="{{ route('dashboard.discounts.mark_as.published', $discount) }}"
                class="d-sm-inline-block btn btn-sm btn-success shadow-sm">
                <i class="fas fa-calendar fa-sm text-white-50"></i>
                @lang('main.publish')
            </a>
        @endif
       
            <a href="{{ route('dashboard.discounts.edit', $discount) }}"
                class="d-sm-inline-block btn btn-sm btn-info shadow-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i>
                @lang('main.edit')
            </a>
 
            <a href="{{ route('dashboard.discounts.destroy',  $discount) }}"
                class="d-sm-inline-block btn btn-sm btn-danger shadow-sm" data-toggle="modal"
                data-target="#deleteModel-{{ $discount->id }}" title="@lang('main.delete')">
                <i class="fas fa-trash fa-sm text-white-50"></i>
                @lang('main.delete')
            </a>
            
            @component('dashboard.components.deleteModelForm')
                @slot('id', $discount->id )
                @slot('deleteTitle', trans('main.discounts') . ' ' . $discount->name_by_lang)
                @slot('url', route('dashboard.discounts.destroy', $discount->id) )
            @endcomponent
         

        <a href="{{ route('dashboard.discounts.create') }}" class="d-sm-inline-block btn btn-sm btn-success shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i>
            @lang('main.add') @lang('main.discounts')
        </a>

        <a href="{{ route('dashboard.discounts.index') }}" class="d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-store fa-sm text-white-50"></i>
            @lang('main.show_all') @lang('main.discounts')
        </a>
        @endadmin  
    </div>
</div>

<!-- Content Row -->
<div class="row">

     <!-- Page Heading -->
     <div class="col-xl-12 col-md-12 mb-3">
        <h1 class="h4 font-weight-bold mb-0 text-gray-800">@lang('main.basic')</h1>
    </div>
    {{-- Basic Info --}}
    <div class="col-xl-12 col-md-12 col-sm-12 mb-3">
        <div class="card border-left-success">

            <div class="card-body">
                <div class="card-title font-weight-bold h5 text-center  row">
                    <div class="card-title col-md-6 col-sm-12 mb-0">@lang('main.created_at'):
                        {{ $discount->created_at->calendar() }} </div>
                    <div class="card-title col-md-6 col-sm-12 mb-0">@lang('main.updated_at'):
                        {{ $discount->updated_at->calendar() }} </div>
                </div>
                <hr>
                <div class="card-text">
                    <div class="row">

                        @include('dashboard.discounts.includes.show._basic_info')
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
  
    <!-- Page Heading -->
    <div class="col-xl-12 col-md-12 mt-4 mb-3">
        <h1 class="h4 font-weight-bold mb-0 text-gray-800">@lang('main.products_in_discount')</h1>
    </div>
    {{-- discounts Info --}}
    <div class="col-xl-12 col-md-12 col-sm-12 mb-3">
        @include('dashboard.discounts.includes.show._products_info')
    </div>

     
</div>
@endsection


@section('scripts')
    {{-- Colcade --}}
    <script src="{{ asset('/dashboard_assets/libs/colcade/colcade.js') }}" type="text/javascript"></script>
    
    {{-- Colcade Init --}}
    <script>
        new Colcade('.grid', {
            columns: '.grid-col',
            items: '.grid-item'   
        });
    </script>

    {{-- Delete Media --}}
    <script>
        deleteMedia('.delete-image', '{{ route("dashboard.discounts.media.destroy", $discount) }}');
    </script>
@endsection