<div class="additions-container row">
    <div class="col-sm-5 d-inline-block">
        <input type="text" name="additions_ar_names[]" required class="form-control mb-1 "
            placeholder="{{ trans('main.name') }}*">
    </div>
   {{--  <div class="col-sm-3 d-inline-block">
        <input type="text" name="additions_en_names[]" required class="form-control mb-1 "
            placeholder="{{ trans('main.name') }}*">
    </div> --}}
    <div class="col-sm-5 d-inline-block">
        <input type="number" name="additions_prices[]" required class="form-control mb-1 "
            placeholder="{{ trans('main.price') }}*">
    </div>
    <div class="col-sm-2 input-group-append mb-1">
        <button id="removeAdditionsRow" type="button" class="btn btn-danger btn-sm"> <i class="fas fa-trash"></i>
        </button>
    </div>
</div>
