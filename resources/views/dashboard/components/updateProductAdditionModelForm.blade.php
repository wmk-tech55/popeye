<div class="modal" tabindex="-1" role="dialog" id="editProductAddition-{{ $addition->id }}">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"> {{ $addition->name_by_lang }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ $url }}" method="POST" enctype="multipart/form-data">
                <div class="modal-body">

                    @csrf
                   @method('patch')

                    {{-- AR Name --}}
                    <div class="form-group mb-4 row">
                        <label class="col-sm-2 col-form-label" for="ar_name">@lang('main.name')<span
                                class="required"></span> </label>
                        <div class="col-sm-10">
                            <input type="text" name="ar_name" value="{{ old('ar_name', $addition->ar_name) }}"
                                id="ar_name" class="form-control @error('ar_name') is-invalid @enderror"
                                placeholder="@lang('main.name')" required>
                            @error('ar_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group mb-4 row one-option-section">
                        <label class="col-sm-2 col-form-label" for="price">@lang('main.price') <span
                                class="required"></span>
                        </label>

                        <div class="col-sm-10">
                            <input type="text" name="price" value="{{ old('price', $addition->price) }}"
                                id="price" class="form-control @error('price') is-invalid @enderror"
                                placeholder="@lang('main.price')" required>
                            @error('price')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                </div>

                <div class="modal-footer">

                    <button type="submit" class="btn btn-success">@lang('main.edit')</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"> @lang('main.cancel') </button>

                </div>
            </form>
        </div>
    </div>
</div>
