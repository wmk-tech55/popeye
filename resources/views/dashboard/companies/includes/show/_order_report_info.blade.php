 <div class="card-title font-weight-bold h5">@lang('main.summary') : </div>
 <table class="table table-hovered table-borderd">

 
     <tbody>

         <tr>
             <td>@lang('main.total_orders_count') </td>
             <td> {{ $company->reports_total['total_order_count'] }} </td>
         </tr>
         <tr>
             <td>@lang('main.orders_not_count') </td>
             <td> {{ $company->reports_total['total_not_paid_order_count'] }} </td>
         </tr>
         <tr>
             <td>@lang('main.total_orders') </td>
             <td> {{ $company->reports_total['total_before_commission'] }} {{ currency() }}</td>
         </tr>

         <tr>
             <td>@lang('main.total_amount_for_the_application')</td>
             <td> {{ $company->reports_total['total_amount_for_the_application'] }} {{ currency() }}</td>
         </tr>
         <tr>
             <td>@lang('main.total_amount_owed_to_the_company')</td>
             <td> {{ $company->reports_total['total_amount_owed_to_the_company'] }} {{ currency() }}</td>
             </th>
         <tr>
             <td class=" text-success">@lang('main.pay_off')</td>
             <td>

                 @if ($company->reports_total['total_not_paid_order_count'] > 0)

                     <a href="#" class="d-sm-inline-block btn btn-sm btn-success shadow-sm" data-toggle="modal"
                         data-target="#payOffModel">
                         @lang('main.pay_off')
                     </a>

                     @component('dashboard.components.payOffModelForm')
                         @slot('id', $company->id)
                     @endcomponent
                 @else
                     <a class="d-sm-inline-block btn btn-sm btn-success shadow-sm" aria-disabled="" disabled>
                         @lang('main.pay_off')
                     </a>
                 @endif

             </td>
             </th>
     </tbody>
 </table>

 <hr>


 <livewire:dashboard.company-order-accounttant-reports :user="$company">
