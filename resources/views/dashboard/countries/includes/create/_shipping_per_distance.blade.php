<table class="table table-hovered table-bordered">
    <th>@lang('main.distance_from')</th>
    <th>@lang('main.distance_to')</th>
    <th>@lang('main.shipping_fee')</th>

    @foreach ($shipping_fee_per_distances as $shipping_fee)
        <tr>
            <td>
                @if ($shipping_fee['is_default_distance'])
                    <span class="badge badge-success"> @lang('main.more_than')</span>
                @endif
                {{ $shipping_fee['distance_from'] }} @lang('main.' . $shipping_fee['unit'])
            </td>
            <td>
                @if (!$shipping_fee['is_default_distance'])
                    {{ $shipping_fee['distance_to'] }} @lang('main.' . $shipping_fee['unit'])
                @else
                    ------
                @endif
            </td>
            <td>
                <input type="text" name="shipping_fees[]"
                    value="{{ $shipping_fee['shipping_fee'] }}"  
                    class="form-control @error('shipping_fee') is-invalid @enderror" placeholder="@lang('main.shiping_fee') ">
              
            </td>

        </tr>
    @endforeach
</table>
