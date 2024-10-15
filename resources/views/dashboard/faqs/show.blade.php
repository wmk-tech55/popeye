@extends('dashboard.layouts.app')

@section('section', $sectionName)

@section('content')

<!-- Page Heading -->
<div class="d-flex flex-column flex-lg-row align-items-center justify-content-between mb-4">

    <h1 class="h3 mb-3 text-gray-800">{{ $sectionName }}</h1>
    <div class="text-center">

        @if ($faq->isActive())
            <a href="{{ route('dashboard.faqs.mark_as.in_active', $faq) }}"
                class="d-sm-inline-block mb-2 btn btn-sm btn-danger shadow-sm">
                <i class="fas fa-times fa-sm text-white-50"></i>
                @lang('main.disabled')
            </a>
        @else
            <a href="{{ route('dashboard.faqs.mark_as.active', $faq) }}"
                class="d-sm-inline-block mb-2 btn btn-sm btn-success shadow-sm">
                <i class="fas fa-check fa-sm text-white-50"></i>
                @lang('main.active')
            </a>
        @endif

        <a href="{{ route('dashboard.faqs.edit', $faq) }}"
            class="d-sm-inline-block mb-2 btn btn-sm btn-info shadow-sm">
            <i class="fas fa-edit fa-sm text-white-50"></i>
            @lang('main.edit')
        </a>

        <a href="{{ route('dashboard.faqs.destroy',  $faq) }}"
            class="d-sm-inline-block mb-2 btn btn-sm btn-danger shadow-sm" data-toggle="modal"
            data-target="#deleteModel-{{ $faq->id }}" title="@lang('main.delete')">
            <i class="fas fa-trash fa-sm text-white-50"></i>
            @lang('main.delete')
        </a>

        <a href="{{ route('dashboard.faqs.create') }}" class="d-sm-inline-block mb-2 btn btn-sm btn-success shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i>
            @lang('main.add') @lang('main.faqs')
        </a>

        <a href="{{ route('dashboard.faqs.index') }}" class="d-sm-inline-block mb-2 btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-comments fa-sm text-white-50"></i>
            @lang('main.show_all') @lang('main.faqs')
        </a>

        @component('dashboard.components.deleteModelForm')
            @slot('id', $faq->id )
            @slot('deleteTitle', trans('main.faqs') . ' ' . $faq->question_by_lang)
            @slot('url', route('dashboard.faqs.destroy', $faq->id) )
        @endcomponent

    </div>
</div>

<!-- Content Row -->
<div class="row">

    <div class="col-xl-12 col-md-12 col-sm-12 mb-3">
        <div class="card  border-left-success">
            
            <div class="card-body">

                <div class="card-title font-weight-bold h5 text-center  row">
                    <div class="card-title col-md-6 col-sm-12 mb-0">@lang('main.created_at'):
                        {{ $faq->created_at->calendar() }} 
                    </div>
                    <div class="card-title col-md-6 col-sm-12 mb-0">@lang('main.updated_at'):
                        {{ $faq->updated_at->calendar() }} 
                    </div>
                </div>
                <hr>
                <div class="card-text">
                    <div class="row">

                        {{-- EN Question --}}
                        <div class="col-md-6 col-sm-12">
                            <div class="h6">
                                <span class="font-weight-bold">@lang('main.en_question'): </span>
                                <span class="text-muted">{{ $faq->en_question }}</span>
                            </div>
                            <hr>
                        </div>

                        {{-- AR Question --}}
                        <div class="col-md-6 col-sm-12">
                            <div class="h6">
                                <span class="font-weight-bold">@lang('main.ar_question'): </span>
                                <span class="text-muted">{{ $faq->ar_question }}</span>
                            </div>
                            <hr>
                        </div>

                        {{-- Status --}}
                        <div class="col-md-12 col-sm-12">
                            <div class="h6">
                                <span class="font-weight-bold">@lang('main.status'): </span>

                                @if ($faq->isActive())
                                    <span class="badge badge-success">@lang("main.active")</span>
                                @else
                                    <span class="badge badge-danger">@lang("main.disabled")</span>
                                @endif
                            </div>
                            <hr>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>

    </div>

    <div class="col-xl-12 col-md-12 col-sm-12 mb-3">
        {{-- English Answer --}}
        <div class="card border-left-primary mb-3">
            <div class="card-body">
                <div class="card-title font-weight-bold h5 text-center text-primary">@lang('main.en_answer')</div>
                <div class="card-text">
                    {!! $faq->en_answer !!}
                </div>
            </div>
        </div>

        {{-- Arabic Answer --}}
        <div class="card border-left-primary mb-3">
            <div class="card-body">
                <div class="card-title font-weight-bold h5 text-center text-primary">@lang('main.ar_answer')</div>
                <div class="card-text">
                    {!! $faq->ar_answer !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection