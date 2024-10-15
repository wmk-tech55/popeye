<div class="modal" tabindex="-1" role="dialog" id="createNewProductVariation">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"> {{ $product->name_by_lang }} </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ $url }}" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <h5 class="modal-title"> @lang('main.add_new_variation_for_product')</h5>
                    @csrf
                    @include('dashboard.products.includes.duplicate._variations_info')
                    <input type="hidden" name="type" value="{{ $product->type }}">
                </div>

                <div class="modal-footer">

                    <button type="submit" class="btn btn-success">@lang('main.add') @lang('main.variation')</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"> @lang('main.cancel') </button>

                </div>
            </form>
        </div>
    </div>
</div>
