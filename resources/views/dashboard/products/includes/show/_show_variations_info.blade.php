<div class="card  border-left-info pb-5">


    <div class="card-title font-weight-bold h5 text-center  text-info my-2">@lang('main.variations')</div>
    <div class="col-md-12">
        <div id="accordion">

{{--             @if ($product->isMultipleOption())

                <div class="m-1">
                    <a href="#"
                        class="d-sm-inline-block btn btn-sm btn-success shadow-sm" data-toggle="modal"
                        data-target="#createNewProductVariation" title="@lang('main.add_new_classification_for_product')">
                        <i class="fas fa-plus fa-sm text-white-50"></i>
                        @lang('main.add_new_variation_for_product')
                    </a>

                    @component('dashboard.components.createNewProductVariationModelForm')
                        @slot('product', $product)
                        @slot('url', route('dashboard.products.new_variation', $product->id))
                    @endcomponent
                </div>
            @endif --}}

            <div class="card">
                <table class="table table-hovered table-bordered">
                    <thead>
                      
                        @if ($product->isMultipleOption())
                            <th>@lang('main.variation')</th>
                        @endif
                        <th>@lang('main.price')</th>
                    </thead>

                    @foreach ($product->productVariations as $variation)
                        <tr>
                            
                            @if ($product->isMultipleOption())
                                <td>{{ $variation->name_by_lang }}</td>
                            @endif
                            <td>{{ $variation->price }} {{ currency() }}</td>
                        </tr>
                    @endforeach
                </table>


            </div>


        </div>
    </div>

</div>
