<div class="card  border-left-danger pb-5">


    <div class="card-title font-weight-bold h5 text-center  text-danger my-2">@lang('main.additions')</div>
    <div class="col-md-12">
        <div id="accordion">


{{--             <div class="m-1">
                <a href="#" class="d-sm-inline-block btn btn-sm btn-success shadow-sm" data-toggle="modal"
                    data-target="#createNewProductAddition" title="@lang('main.add_new_addition_for_product')">
                    <i class="fas fa-plus fa-sm text-white-50"></i>
                    @lang('main.add_new_addition_for_product')
                </a>

                @component('dashboard.components.createNewProductAdditionModelForm')
                    @slot('product', $product)
                    @slot('url', route('dashboard.products.new_addition', $product->id))
                @endcomponent
            </div> --}}


            <div class="card">
                <table class="table table-hovered table-bordered">
                    <thead>

                        <th>@lang('main.addition')</th>
                        <th>@lang('main.price')</th>
                    </thead>

                    @forelse ($product->productAdditions as $addition)
                    <tr>
                        <td>{{ $addition->name_by_lang }}</td>
                        <td>{{ $addition->price }} {{ currency() }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2">@lang('main.not_found')</td>
                    
                    </tr>
                    @endforelse
                    
                </table>


            </div>


        </div>
    </div>

</div>
