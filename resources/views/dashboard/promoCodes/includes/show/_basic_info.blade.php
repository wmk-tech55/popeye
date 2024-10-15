@admin
{{-- Owner --}}
<div class="col-md-12 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang('main.creator'): </span>

        @if (!! $promoCode->creator)
        <a href="{{ $promoCode->creator->path() }}" target="_blank"
            rel="noopener noreferrer">{{ $promoCode->creator->full_name }}</a>
        @else
        <span class="text-muted">@lang('main.not_found')</span>
        @endif
    </div>
    <hr>
</div>
@endadmin

{{-- code --}}
<div class="col-md-12 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang('main.code'): </span>
        <span class="text-muted"  >
             <input type="text" class="btn"  id="myInput" value="{{ $promoCode->code }}"  readonly> </span>
             <button class="btn btn-secondary btn-sm"  id="demo" onclick="copyToClipboard()" title="@lang('main.copy')"> 
                <i class="fas fa-clipboard"></i> @lang('main.copy')
            </button>
        </span>


        <!-- The text field -->

    </div>
    <hr>
</div>

{{-- discount --}}
<div class="col-md-6 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang('main.discount'): </span>

        <span class="text-muted">{{ $promoCode->discount }} %</span>
    </div>
    <hr>
</div>


{{-- number of uses --}}
<div class="col-md-6 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang('main.number_of_uses'): </span>

        <span class="text-muted">{{ $promoCode->number_of_uses }} </span>
    </div>
    <hr>
</div>


{{-- expiry date --}}
<div class="col-md-6 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang('main.expiry_date'): </span>

        <span class="text-muted">{{ $promoCode->expiry_date }}</span>
    </div>
    <hr>
</div>



<div class="col-md-6 col-sm-12">
    <div class="h6">
        <span class="font-weight-bold">@lang('main.status'): </span>

        @if ($promoCode->active())
        <span class="badge badge-success">@lang("main.published")</span>
        @else
        <span class="badge badge-warning">@lang("main.disabled")</span>
        @endif
    </div>
    <hr>
</div>