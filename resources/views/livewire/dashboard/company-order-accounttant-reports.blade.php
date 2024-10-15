<div class="container">
    <hr>

    <div class="card-title font-weight-bold h5">@lang('main.reports') : </div>
    <table class="table table-hovered table-borderd">

        <th>@lang('main.invoice_id') </th>
        <th>@lang('main.total') </th>
        <th>@lang('main.commission_amount') </th>
        <th>@lang('main.amount_for_the_application')</th>
        <th>@lang('main.amount_owed_to_the_company')</th>
        <th>@lang('main.status')</th>

        <tbody>

            @foreach ($reports as $report)

            <tr>
                <td>{{$report->order->invoice_id}}  </td>
                <td>{{$report->total_before_commission}} {{currency()}} </td>
                <td>{{ $report->commission_percentage}} % </td>
                <td>{{$report->total_before_commission - $report->total_after_commission}} {{currency()}} </td>
                <td>{{$report->total_after_commission}} {{currency()}} </td>
                <td>
                    @if ($report->isPaid())
                    <span class="badge badge-success">@lang('main.paid')</span>
                    @else
                    <span class="badge badge-danger">@lang('main.not_paid')</span>
                    @endif
                </td>

            </tr>

            @endforeach

        </tbody>

    </table>

    {{ $reports->links() }}



</div>