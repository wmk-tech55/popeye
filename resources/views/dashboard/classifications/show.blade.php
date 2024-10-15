@extends('dashboard.layouts.app')

@section('styles')
<style>
    .classification-box {
        height: 186px;
    }
</style>
@endsection

@section('section', $sectionName)

@section('content')


<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{ $sectionName }}</h1>
    <div>
        <a href="{{ route('dashboard.classifications.edit', $classification) }}"
            class="d-sm-inline-block btn btn-sm btn-info shadow-sm">
            <i class="fas fa-edit fa-sm text-white-50"></i>
            @lang('main.edit')
        </a>

        <a href="{{ route('dashboard.classifications.destroy',  $classification) }}"
            class="d-sm-inline-block btn btn-sm btn-danger shadow-sm" data-toggle="modal"
            data-target="#deleteModel-{{ $classification->id }}" title="@lang('main.delete')">
            <i class="fas fa-trash fa-sm text-white-50"></i>
            @lang('main.delete')
        </a>

        <a href="{{ route('dashboard.classifications.create') }}"
            class="d-sm-inline-block btn btn-sm btn-success shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i>
            @lang('main.add') @lang('main.classifications')
        </a>

        <a href="{{ route('dashboard.classifications.index') }}"
            class="d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-globe fa-sm text-white-50"></i>
            @lang('main.show_all') @lang('main.classifications')
        </a>

        @component('dashboard.components.deleteModelForm')
        @slot('id', $classification->id )
        @slot('deleteTitle', trans('main.classifications') . ' ' . $classification->name_by_lang)
        @slot('url', route('dashboard.classifications.destroy', $classification->id) )
        @endcomponent

    </div>
</div>



<!-- Content Row -->
<div class="row">

    <div class="col-xl-12 col-md-12 col-sm-12 mb-3">
        <div class="card  border-left-success">
            <div class="row no-gutters">

                <div class="col-md-12 col-sm-12">
                    <div class="card-body">
                        <div class="card-title font-weight-bold h5 text-center  row">
                            <div class="card-title col-md-6 col-sm-12 mb-0">@lang('main.created_at'):
                                {{ $classification->created_at->calendar() }} </div>
                            <div class="card-title col-md-6 col-sm-12 mb-0">@lang('main.updated_at'):
                                {{ $classification->updated_at->calendar() }} </div>
                        </div>
                        <hr>
                        <div class="card-text">
                            <div class="row">

                                {{-- EN Name --}}
                                <div class="col-md-6 col-sm-12">
                                    <div class="h6">
                                        <span class="font-weight-bold">@lang('main.en_name'): </span>
                                        <span class="text-muted">{{ $classification->en_name }}</span>
                                    </div>
                                    <hr>
                                </div>

                                {{-- AR Name --}}
                                <div class="col-md-6 col-sm-12">
                                    <div class="h6">
                                        <span class="font-weight-bold">@lang('main.ar_name'): </span>
                                        <span class="text-muted">{{ $classification->ar_name }}</span>
                                    </div>
                                    <hr>
                                </div>

                                {{-- AR Name --}}
                                <div class="col-md-6 col-sm-12">
                                    <div class="h6">
                                        <span class="font-weight-bold">@lang('main.categorization'): </span>
                                        <span class="text-muted">  {{ $classification->categorization->name_by_lang }}</span>
                                    </div>
                                    <hr>
                                </div>



                                 
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection