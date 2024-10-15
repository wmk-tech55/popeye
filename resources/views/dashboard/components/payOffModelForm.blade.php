<div class="modal" tabindex="-1" role="dialog" id="payOffModel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('main.pay_off') </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                @lang('main.confirm_paying_off')

            </div>
            <div class="modal-footer">
                <form action="{{route('dashboard.reports.pay_off_order_reports',$id)}}" method="post">
                    @csrf
                    @method('patch')
                    <button type="submit" class="btn btn-success m-1"> @lang('main.pay') </button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"> @lang('main.cancel') </button>
                </form>
            </div>
        </div>
    </div>
</div>