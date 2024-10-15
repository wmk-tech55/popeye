<div class="modal animated--grow-in" tabindex="-1" role="dialog" id="balanceModel-{{$id}}">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('main.balance') | {{ $balanceTitle ?? '' }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body border-left-success">
                
                <h6 class="font-weight-bold"> @lang('main.ask-balance-pay') </h6>
 
                
            </div>
            <div class="modal-footer">
                <form action="{{ $url }}" method="get">
                    @csrf
                    <button type="submit" class="btn btn-success mr-1"> @lang('main.approval') </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"> @lang('main.cancel') </button>
                </form>
            </div>
        </div>
    </div>
</div>