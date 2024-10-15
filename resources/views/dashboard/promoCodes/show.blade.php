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
        @if ($promoCode->Active())

        <a href="{{ route('dashboard.promoCodes.mark_as.inActive', $promoCode) }}"
            class="d-sm-inline-block btn btn-sm btn-warning shadow-sm">
            <i class="fas fa-clock fa-sm text-white-50"></i>
            @lang('main.disabled')
        </a>
        @else
        <a href="{{ route('dashboard.promoCodes.mark_as.active', $promoCode) }}"
            class="d-sm-inline-block btn btn-sm btn-success shadow-sm">
            <i class="fas fa-calendar fa-sm text-white-50"></i>
            @lang('main.active')
        </a>
        @endif

        <a href="{{ route('dashboard.promoCodes.edit', $promoCode) }}"
            class="d-sm-inline-block btn btn-sm btn-info shadow-sm">
            <i class="fas fa-edit fa-sm text-white-50"></i>
            @lang('main.edit')
        </a>

        <a href="{{ route('dashboard.promoCodes.destroy',  $promoCode) }}"
            class="d-sm-inline-block btn btn-sm btn-danger shadow-sm" data-toggle="modal"
            data-target="#deleteModel-{{ $promoCode->id }}" title="@lang('main.delete')">
            <i class="fas fa-trash fa-sm text-white-50"></i>
            @lang('main.delete')
        </a>

        @component('dashboard.components.deleteModelForm')
        @slot('id', $promoCode->id )
        @slot('deleteTitle', trans('main.promoCodes') . ' ' . $promoCode->code)
        @slot('url', route('dashboard.promoCodes.destroy', $promoCode->id) )
        @endcomponent


        <a href="{{ route('dashboard.promoCodes.create') }}" class="d-sm-inline-block btn btn-sm btn-success shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i>
            @lang('main.add') @lang('main.promoCodes')
        </a>

        <a href="{{ route('dashboard.promoCodes.index') }}" class="d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-store fa-sm text-white-50"></i>
            @lang('main.show_all') @lang('main.promoCodes')
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
                        {{ $promoCode->created_at->calendar() }} </div>
                    <div class="card-title col-md-6 col-sm-12 mb-0">@lang('main.updated_at'):
                        {{ $promoCode->updated_at->calendar() }} </div>
                </div>
                <hr>
                <div class="card-text">
                    <div class="row">

                        @include('dashboard.promoCodes.includes.show._basic_info')

                    </div>
                </div>
            </div>
        </div>
    </div>





</div>
@endsection


@section('scripts')
{{-- style --}}

<script>
    function copyToClipboard() {
        var copyText = document.getElementById("myInput");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        document.execCommand("copy");
            
        /* Alert the copied text */
        notifyMessage('success', '{{ trans("main.copied_successfully") }}');
    }
</script>
@endsection