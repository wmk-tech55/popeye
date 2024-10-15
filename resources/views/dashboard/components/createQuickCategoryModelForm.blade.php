<div class="modal" tabindex="-1" role="dialog" id="createQuickCategory">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('main.add') @lang('main.category')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="{{ route('dashboard.categories.store') }}" method="POST" enctype="multipart/form-data">

                    @csrf

                    {{-- EN Name --}}
                    <div class="form-group row  mb-4">
                        <label class="col-sm-5 col-form-label" for="en_name">@lang('main.en_name')<span
                                class="required"></span> </label>
                        <div class="col-sm-6">
                            <input type="text" name="en_name" value="{{ old('en_name') }}" id="en_name_category"
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
                            <input type="text" name="ar_name" value="{{ old('ar_name') }}" id="ar_name_category"
                                class="form-control @error('ar_name') is-invalid @enderror"
                                placeholder="@lang('main.ar_name')" required>
                            @error('ar_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
  
                        
                    {{-- Categories --}}
                    <div class="form-group mb-4 row">
                        <label class="col-sm-5 col-form-label" for="parent_id">@lang('main.categories')<span
                                class="required"></span> </label>
                        <div class="col-sm-6">
                            <select name="parent_id" id="parent_category_id"
                                class="form-control select2  @error('parent_id') is-invalid @enderror"
                                data-placeholder="@lang('main.categories')">
                                <option disabled selected>@lang('main.category')</option>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ in_array($category->id, old('parent_id') ?? []) ? 'selected' : '' }}>
                                    {{ $category->name_by_lang }}
                                </option>
                                @endforeach
                            </select>

                            @error('parent_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                     {{-- Icon --}}
                     <div class="form-group mb-4 row">
                        <label class="col-sm-5 col-form-label" for="icon">@lang('main.icon')<span
                                class="required"></span> </label>
                        <div class="col-sm-6">

                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-preview thumbnail" data-trigger="fileinput">

                                </div>
                                <div>
                                    <span class="btn blue btn-outline btn-file">
                                        <span class="fileinput-new btn btn-success"> @lang('main.select_image')
                                        </span>
                                        <span class="fileinput-exists btn btn-info"> @lang('main.change') </span>
                                        <input type="file" name="icon" id="icon" accept=".jpg,.png,.jpeg" required>
                                    </span>
                                    <a href="javascript:;" class="btn btn-danger fileinput-exists"
                                        data-dismiss="fileinput"> @lang('main.remove') </a>
                                </div>
                            </div>



                            @error('icon')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="form-group mb-4 row">
                        <label class="col-sm-5 col-form-label" for="status">@lang('main.status')<span
                                class="required"></span> </label>
                        <div class="col-sm-6">
                            <input type="hidden" name="status" value="disable">

                            <input type="checkbox" id="status" name="status" class="switch category_status" data-on-color="success"
                                data-off-color="danger" data-on-text="@lang('main.active')"
                                data-off-text="@lang('main.disabled')" value="active">

                            @error('status')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>

                </form>

            </div>
            <div class="modal-footer">
                  
                    <button type="button" id="quick-category-add" class="btn btn-info">@lang('main.add') @lang('main.category')</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"> @lang('main.cancel') </button>
                 
            </div>
        </div>
    </div>
</div>