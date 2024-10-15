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

    {{-- <div class="alert alert-warning">
    <i class="fas fa-exclamation-triangle"></i>
    @lang('main.change_order_status_message')
</div> --}}

    {{-- <div class="alert alert-info">
    <i class="fas fa-exclamation-circle"></i>
    @lang('main.default_price_ratio_message')
</div> --}}

    <!-- Page Heading -->

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            @admin
                @if ($product->isPublished())
                    <a href="{{ route('dashboard.products.mark_as.pending', $product) }}"
                        class="d-sm-inline-block btn btn-sm btn-warning shadow-sm">
                        <i class="fas fa-clock fa-sm text-white-50"></i>
                        @lang('main.pending')
                    </a>
                @else
                    <a href="{{ route('dashboard.products.mark_as.published', $product) }}"
                        class="d-sm-inline-block btn btn-sm btn-success shadow-sm">
                        <i class="fas fa-calendar fa-sm text-white-50"></i>
                        @lang('main.publish')
                    </a>
                @endif
            @endadmin

            @can('view', $product)


                <a href="{{ route('dashboard.products.edit', $product) }}"
                    class="d-sm-inline-block btn btn-sm btn-info shadow-sm">
                    <i class="fas fa-edit fa-sm text-white-50"></i>
                    @lang('main.edit')
                </a>

                <a href="{{ route('dashboard.products.destroy', $product) }}"
                    class="d-sm-inline-block btn btn-sm btn-danger shadow-sm" data-toggle="modal"
                    data-target="#deleteModel-{{ $product->id }}" title="@lang('main.delete')">
                    <i class="fas fa-trash fa-sm text-white-50"></i>
                    @lang('main.delete')
                </a>

                @component('dashboard.components.deleteModelForm')
                    @slot('id', $product->id)
                    @slot('deleteTitle', trans('main.products') . ' ' . $product->name_by_lang)
                    @slot('url', route('dashboard.products.destroy', $product->id))
                @endcomponent
            @endcan


            <a href="{{ route('dashboard.products.create') }}" class="d-sm-inline-block btn btn-sm btn-success shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i>
                @lang('main.add') @lang('main.products')
            </a>

            <a href="{{ route('dashboard.products.index') }}" class="d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plane fa-sm text-white-50"></i>
                @lang('main.show_all') @lang('main.products')
            </a>

        </div>
    </div>

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $sectionName }}</h1>
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
                            {{ $product->created_at->calendar() }} </div>
                        <div class="card-title col-md-6 col-sm-12 mb-0">@lang('main.updated_at'):
                            {{ $product->updated_at->calendar() }} </div>
                    </div>
                    <hr>
                    <div class="card-text">
                        <div class="row">

                            @include('dashboard.products.includes.show._basic_info')


                        </div>
                    </div>


                </div>
            </div>
        </div>

        <!-- Page Heading -->
        <div class="col-xl-12 col-md-12 mt-4 mb-3">
            <h1 class="h4 font-weight-bold mb-0 text-gray-800">@lang('main.product_variations')</h1>
        </div>

        <div class="col-xl-12 col-md-12 col-sm-12 mb-3">
            @include('dashboard.products.includes.show._show_variations_info')
        </div>
        <!-- Page Heading -->
        <div class="col-xl-12 col-md-12 mt-4 mb-3">
            <h1 class="h4 font-weight-bold mb-0 text-gray-800">@lang('main.additions')</h1>
        </div>

        <div class="col-xl-12 col-md-12 col-sm-12 mb-3">
            @include('dashboard.products.includes.show._show_additions_info')
        </div>



        <!-- Page Heading -->
        <div class="col-xl-12 col-md-12 mt-4 mb-3">
            <h1 class="h4 font-weight-bold mb-0 text-gray-800">@lang('main.overview')</h1>
        </div>
        {{-- categories Info --}}
        <div class="col-xl-12 col-md-12 col-sm-12 mb-3">
            @include('dashboard.products.includes.show._overview_info')
        </div>




        {{-- Media --}}
        <div class="col-xl-12 col-md-12 col-sm-12 mb-3">
            @include('dashboard.products.includes.show._media')
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
        deleteMedia('.delete-image', '{{ route('dashboard.products.media.destroy', $product) }}');
    </script>


    <!--------------- add new categorization and classification to product------------>
    <script>
        $("#addCategorizationRow").click(function() {
            $.ajax({
                url: "{{ route('dashboard.products.get_categorizations_and_classifications') }}",
            }).done(function(data) {
                $('.classification_id').select2('destroy');
                $('.categorizations_id').select2('destroy');
                $('#NewCategorizationRow').append(data);
                $('.categorizations_id').select2({
                    theme: 'bootstrap'
                });
                $('.classification_id').select2({
                    theme: 'bootstrap'
                });
            });
        });
    </script>

    <script>
        $(document).on('change', '#categorization_id', function() {
            var categorization_id = "{{ $product->categorization_id }}";

            $.ajax({
                url: "{{ route('dashboard.products.get_categorizations_and_classifications') }}",
                data: {
                    categorization_id: categorization_id
                },
            }).done(function(data) {
                $('.classifications_id').select2('destroy');
                $('#newRow').html(data);
                $('.price-and-classification-section').removeClass('d-none');
                $('.classifications_id').select2({
                    theme: 'bootstrap'
                });
            });

        });
    </script>

    <!--------------- add new Variation to product------------->
    <script type="text/javascript">
        $("#addVariationRow").click(function() {

            var html = '';
            html += '<div class="special-price-container">';
            html += '<div class="col-sm-4 d-inline-block">';
            html +=
                '<input type="text" name="variations_ar_names[]" class="form-control mb-1 " placeholder="{{ trans('main.ar_name') }}*"  >';
            html += '</div>';
            html += '<div class="col-sm-4 d-inline-block">';
            html +=
                '<input type="text" name="variations_en_names[]" class="form-control mb-1 " placeholder="{{ trans('main.en_name') }}*"  >';
            html += '</div>';
            html += '<div class="col-sm-2 d-inline-block">';
            html +=
                '<input type="number" name="variations_prices[]" class="form-control mb-1 " placeholder="{{ trans('main.price') }}*"  >';
            html += '</div>';
            html += '<div class="col-sm-2 d-inline-block input-group-append mb-1">';
            html +=
                '<button id="removeRow" type="button" class="btn btn-danger"> <i class="fas fa-trash"></i>  </button>';
            html += '</div>';
            html += '</div>';

            $('#addVariationRowData').append(html);
        });

        $(document).on('click', '#removeRow', function() {
            $(this).closest('.special-price-container').remove();
        });
    </script>
    <!--------------- add new additions to product------------->
    <script type="text/javascript">
        $("#newAdditionRow").click(function() {

            var html = '';
            html += '<div class="additions-container">';
            html += '<div class="col-sm-4 d-inline-block">';
            html +=
                '<input type="text" name="additions_ar_names[]" class="form-control mb-1 " placeholder="{{ trans('main.ar_name') }}*"  >';
            html += '</div>';
            html += '<div class="col-sm-4 d-inline-block">';
            html +=
                '<input type="text" name="additions_en_names[]" class="form-control mb-1 " placeholder="{{ trans('main.en_name') }}*"  >';
            html += '</div>';
            html += '<div class="col-sm-2 d-inline-block">';
            html +=
                '<input type="number" name="additions_prices[]" class="form-control mb-1 " placeholder="{{ trans('main.price') }}*"  >';
            html += '</div>';
            html += '<div class="col-sm-2 d-inline-block input-group-append mb-1">';
            html +=
                '<button id="removeAdditionsRow" type="button" class="btn btn-danger"> <i class="fas fa-trash"></i>  </button>';
            html += '</div>';
            html += '</div>';

            $('#newAdditionRowContainer').append(html);
        });

        $(document).on('click', '#removeAdditionsRow', function() {
            $(this).closest('.additions-container').remove();
        });
    </script>



@endsection
