<div class="col-xl-12 col-md-12 col-sm-12 mb-3">
    <div class="card border-left-success">

        <div class="card-body">
              <div class="table-responsive mt-3"> 
                  <table class="table table-bordered table-hovered text-center ">
                      <tbody>
                          <th>@lang('main.product')</th>
                          <th>@lang('main.quantity')</th>
                          <th>@lang('main.price')</th>
                          <th>@lang('main.total')</th>
 
                          @foreach ($order->products as $product)
                              <tr>
                                  <td>
                                      <a href="{{ $product->product->product ? $product->product->path() : '#' }}"
                                          class="list-group-item list-group-item-action" target="_blank"
                                          rel="noopener noreferrer">{{ $product->product->name_by_lang }}</a>

                                  </td>
                                  <td> {{ $product->quantity }}</td>
                                  <td> {{ $product->price }}</td>
                                  <td> {{ $product->final_price }}</td>
                                 
                              </tr>
                              @if ($product->additions)
                                  
                              <tr>
                                <td>@lang('main.additions')</td>
                                <td colspan="2">
                                    <table class="table table-borderd table-hovered">
                                        <th>@lang('main.addition')</th>
                                        <th>@lang('main.quantity')</th>
                                        <th>@lang('main.price')</th>
                                        <th>@lang('main.total')</th>
                                        @foreach ($product->additions as $addition)
                                            
                                        <tr>
                                            <td>{{$addition->addition->name_by_lang}}</td>
                                            <td>{{$addition->quantity}}</td>
                                            <td>{{$addition->price}}</td>
                                            <td>{{$addition->final_price}}</td>
                                        </tr>
                                        @endforeach
                                    </table>
                                </td>
                                <td>{{$product->additions()->sum('final_price')}}</td>
                              </tr>
                              @endif
                          @endforeach


                          <tr>
                              <th class="text-success" colspan='3'>@lang('site.total')</th>

                              <td class="text-success">{{ $order->total }} @lang('currency.sar')</td>
                          </tr>
                      </tbody>
                  </table>
              </div>
          </div>
      </div>
  </div>
