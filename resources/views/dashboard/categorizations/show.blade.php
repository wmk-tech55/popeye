@extends('dashboard.layouts.app')

@section('styles')
<style>
    .categorization-box {
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
        <a href="{{ route('dashboard.categorizations.edit', $categorization) }}"
            class="d-sm-inline-block btn btn-sm btn-info shadow-sm">
            <i class="fas fa-edit fa-sm text-white-50"></i>
            @lang('main.edit')
        </a>

        <a href="{{ route('dashboard.categorizations.destroy',  $categorization) }}"
            class="d-sm-inline-block btn btn-sm btn-danger shadow-sm" data-toggle="modal"
            data-target="#deleteModel-{{ $categorization->id }}" title="@lang('main.delete')">
            <i class="fas fa-trash fa-sm text-white-50"></i>
            @lang('main.delete')
        </a>

        <a href="{{ route('dashboard.categorizations.create') }}"
            class="d-sm-inline-block btn btn-sm btn-success shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i>
            @lang('main.add') @lang('main.categorizations')
        </a>

        <a href="{{ route('dashboard.categorizations.index') }}"
            class="d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-globe fa-sm text-white-50"></i>
            @lang('main.show_all') @lang('main.categorizations')
        </a>

        @component('dashboard.components.deleteModelForm')
        @slot('id', $categorization->id )
        @slot('deleteTitle', trans('main.categorizations') . ' ' . $categorization->name_by_lang)
        @slot('url', route('dashboard.categorizations.destroy', $categorization->id) )
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
                                {{ $categorization->created_at->calendar() }} </div>
                            <div class="card-title col-md-6 col-sm-12 mb-0">@lang('main.updated_at'):
                                {{ $categorization->updated_at->calendar() }} </div>
                        </div>
                        <hr>
                        <div class="card-text">
                            <div class="row">

                                {{-- EN Name --}}
                                <div class="col-md-6 col-sm-12">
                                    <div class="h6">
                                        <span class="font-weight-bold">@lang('main.en_name'): </span>
                                        <span class="text-muted">{{ $categorization->en_name }}</span>
                                    </div>
                                    <hr>
                                </div>

                                {{-- AR Name --}}
                                <div class="col-md-6 col-sm-12">
                                    <div class="h6">
                                        <span class="font-weight-bold">@lang('main.ar_name'): </span>
                                        <span class="text-muted">{{ $categorization->ar_name }}</span>
                                    </div>
                                    <hr>
                                </div>

                                {{-- Icon --}}
                                <div class="col-md-12 col-sm-12">
                                    <div class="h6">
                                        <span class="font-weight-bold">@lang('main.icon'): </span>
                                        <span class="text-muted">
                                            <img width="400" src="{{ $categorization->mainMediaUrl('icon') }}"
                                                alt="{{ $categorization->name_by_lang }}">
                                        </span>
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