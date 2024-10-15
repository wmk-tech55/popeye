<form action="{{ route('dashboard.orders.update', $order) }}" method="POST" class="status-form">
    @csrf
    @method('PATCH')
 


    {{-- approval  Status --}}
    <div class="col-md-4 col-sm-12">
        <div class="h6">
            <span class="font-weight-bold">@lang('main.approval_status'): </span>

            <input type="hidden" name="is_approved" value="0">

            <input type="checkbox" id="is_approved" name="is_approved" class="switch" data-on-color="success"
                data-off-color="danger" data-on-text="<i class='fa fa-check' aria-hidden='true'></i>"
                data-off-text="<i class='fa fa-times' aria-hidden='true'></i>" value="1"
                {{ $order->isApproved() ? 'checked' : '' }}>

            @error('is_approved')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <hr>
    </div>

    
 
    {{--  shipping  Status --}}
    <div class="col-md-4 col-sm-12">
        <div class="h6">
            <span class="font-weight-bold">@lang('main.shipping_status'): </span>
 
 

            <input type="hidden" name="in_shipping" value="0">

            <input type="checkbox" id="in_shipping" name="in_shipping" class="switch" data-on-color="success"
                data-off-color="danger" data-on-text="<i class='fa fa-check' aria-hidden='true'></i>"
                data-off-text="<i class='fa fa-times' aria-hidden='true'></i>" value="1"
                {{ $order->isShipped() ? 'checked' : '' }}>

            @error('in_shipping')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <hr>
    </div>


    {{-- delivery  Status --}}

    <div class="col-md-4 col-sm-12">
        <div class="h6">
            <span class="font-weight-bold">@lang('main.delivery_status'): </span>

{{--        
               
            @if ($order->isDelivered() == 0)
           
                <span class='badge custom-badge badge-warning'>@lang('main.not_delivered')</span>
           
              @else
                 <span class='badge custom-badge badge-success  '>@lang('main.delivered')</span>
            @endif --}}

            <input type="hidden" name="is_delivered" value="0">

            <input type="checkbox" id="is_delivered" name="is_delivered" class="switch" data-on-color="success"
                data-off-color="danger" data-on-text="<i class='fa fa-check' aria-hidden='true'></i>"
                data-off-text="<i class='fa fa-times' aria-hidden='true'></i>" value="1"
                {{ $order->isDelivered() ? 'checked' : '' }}>

            @error('is_delivered')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <hr>
    </div>
 
   
    {{-- cancelation Status --}}
    {{-- <div class="col-md-4 col-sm-12">
        <div class="h6">
            <span class="font-weight-bold">@lang('main.cancelation_status'): </span>
            
            @if ($order->isCancelled() == 0)
           
                <span class='badge custom-badge badge-success'>@lang('main.not_cancelled')</span>
           
              @else
                 <span class='badge custom-badge badge-danger  '>@lang('main.canceled')</span>
            @endif --}}
            

            {{-- <input type="hidden" name="is_cancelled" value="0" >

            <input type="checkbox" id="is_cancelled" disabled name="is_cancelled" class="switch" data-on-color="success"
                data-off-color="danger" data-on-text="<i class='fa fa-check' aria-hidden='true'></i>"
                data-off-text="<i class='fa fa-times' aria-hidden='true'></i>" value="1"
                {{ $order->isCancelled() ? '' : 'checked' }}>

            @error('is_cancelled')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror --}}
        {{-- </div>
        <hr>
    </div> --}}

    <div class="col-md-12 col-sm-12 mt-3 ">
 
        <button type="submit" class="btn btn-sm btn-info">@lang('main.edit') @lang('main.order')</button>
 
    </div>
</form>