<div class="modal" tabindex="-1" role="dialog" id="createQuickColor">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('main.add') @lang('main.colors')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="{{ route('dashboard.colors.store') }}" method="POST" enctype="multipart/form-data">

                    @csrf

                    {{-- EN Name --}}
                    <div class="form-group row  mb-4">
                        <label class="col-sm-5 col-form-label" for="en_name">@lang('main.en_name')<span
                                class="required"></span> </label>
                        <div class="col-sm-6">
                            <input type="text" name="en_name" value="{{ old('en_name') }}" id="en_name_color"
                                class="form-control @error('en_name') is-invalid @enderror"
                                placeholder="@lang('main.en_name')" required>
                            @error('en_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    {{-- AR Name --}}
                    <div class="form-group  row  mb-4">
                        <label class="col-sm-5 col-form-label" for="ar_name">@lang('main.ar_name')<span
                                class="required"></span> </label>
                        <div class="col-sm-6">
                            <input type="text" name="ar_name" value="{{ old('ar_name') }}" id="ar_name_color"
                                class="form-control @error('ar_name') is-invalid @enderror"
                                placeholder="@lang('main.ar_name')" required>
                            @error('ar_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
 
 
                </form>

            </div>
            <div class="modal-footer">
                
                    <button type="button" id="quick-color-add" class="btn btn-info">@lang('main.add') @lang('main.color')</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"> @lang('main.cancel') </button>
                 
            </div>
        </div>
    </div>
</div>