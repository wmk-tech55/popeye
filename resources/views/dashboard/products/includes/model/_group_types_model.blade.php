<div class="modal" tabindex="-1" role="dialog" id="groupTypes">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('main.group_types') </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
            <img src="{{asset('assets/img/group-types-'.getLanguage().'.png')}}" alt="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"> @lang('main.cancel') </button>
            </div>
        </div>
    </div>
</div>