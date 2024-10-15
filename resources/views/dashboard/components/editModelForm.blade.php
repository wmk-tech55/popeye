 
<div class="modal text-align-initial animated--grow-in" tabindex="-1" role="dialog" id="editModel-{{ $id }}">
    <div class="modal-dialog {{ (isset($largeModal) && $largeModal === true) ? 'modal-xl' : ' ' }}" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('main.edit') {{ $editTitle ?? '' }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ $url }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <div class="modal-body">
                    
                    {{ $slot }}
                    
                </div>
                <div class="modal-footer">                    
                    <button type="submit" class="btn btn-info"> @lang('main.edit') </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"> @lang('main.cancel') </button>
                </div>
            </form>
        </div>
    </div>
</div>