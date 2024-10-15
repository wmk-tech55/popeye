<div class="modal" tabindex="-1" role="dialog" id="deleteWalletBalance">
    <div class=" modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('main.delete_wallet_balance') </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ $url }}" method="POST">
                @csrf
                @method('delete')
                <div class="modal-body">
  
                    <h5 class="font-weight-bold"> @lang('main.ask-delete-balance') </h5>
      
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
 
                </div>
                <div class="modal-footer">

                    <button type="submit"  class="btn btn-danger">@lang('main.delete_wallet_balance')
                        </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"> @lang('main.cancel') </button>

                </div>
            </form>
        </div>
    </div>
</div>
