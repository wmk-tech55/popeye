@extends('dashboard.layouts.app')

@section('section', $sectionName)

@section('styles')
    <style>
        .grid-col {
            flex: 1;
            padding: 0 .1em;
        }
        form {
            margin: 15px;
      }
    </style>
     
@endsection

@section('content')

 
 

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{ $sectionName }}</h1>
    <div>
        @admin
        {{-- @if ($banner->isPublished())
            <a href="{{ route('dashboard.banners.mark_as.pending', $banner) }}"
                class="d-sm-inline-block btn btn-sm btn-warning shadow-sm">
                <i class="fas fa-clock fa-sm text-white-50"></i>
                @lang('main.pending')
            </a>
        @else
            <a href="{{ route('dashboard.banners.mark_as.published', $banner) }}"
                class="d-sm-inline-block btn btn-sm btn-success shadow-sm">
                <i class="fas fa-calendar fa-sm text-white-50"></i>
                @lang('main.publish')
            </a>
        @endif --}}
       
            <a href="{{ route('dashboard.banners.edit', $banner) }}"
                class="d-sm-inline-block btn btn-sm btn-info shadow-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i>
                @lang('main.edit')
            </a>
 
            <a href="{{ route('dashboard.banners.destroy',  $banner) }}"
                class="d-sm-inline-block btn btn-sm btn-danger shadow-sm" data-toggle="modal"
                data-target="#deleteModel-{{ $banner->id }}" title="@lang('main.delete')">
                <i class="fas fa-trash fa-sm text-white-50"></i>
                @lang('main.delete')
            </a>
            
            @component('dashboard.components.deleteModelForm')
                @slot('id', $banner->id )
                @slot('deleteTitle', trans('main.banners') . ' ' . $banner->name_by_lang)
                @slot('url', route('dashboard.banners.destroy', $banner->id) )
            @endcomponent
         

        <a href="{{ route('dashboard.banners.create') }}" class="d-sm-inline-block btn btn-sm btn-success shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i>
            @lang('main.add') @lang('main.banners')
        </a>

        <a href="{{ route('dashboard.banners.index') }}" class="d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-store fa-sm text-white-50"></i>
            @lang('main.show_all') @lang('main.banners')
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
                        {{ $banner->created_at->calendar() }} </div>
                    <div class="card-title col-md-6 col-sm-12 mb-0">@lang('main.updated_at'):
                        {{ $banner->updated_at->calendar() }} </div>
                </div>
                <hr>
                <div class="card-text">
                    <div class="row">

                        @include('dashboard.banners.includes.show._basic_info')
                        
                        <hr>

                        @include('dashboard.banners.includes.show._media')


                    </div>
                </div>
            </div>
        </div>
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
        deleteMedia('.delete-image', '{{ route("dashboard.banners.media.destroy", $banner) }}');
    </script>
     
@endsection