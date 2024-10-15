@component('mail::message')
{{-- @component('mail::promotion')
<img src="{{ asset('/assets/img/logo.png') }}" alt="{{ getSettings()->name }}">
@endcomponent --}}

# @lang($subject)

@lang($message)
<br>
<hr>
<strong>
@lang('main.invoice_id') :
</strong>
{{ $order->invoice_id }}
<br />

<hr>
<h5>@lang('main.driver') :</h5>

<strong>
@lang('main.name') :
</strong>
{{ $order->provider->full_name }}
<br />
<strong>
@lang('main.phone') :
</strong>
{{ $order->provider->phone }}

@lang('notifications.thanks'),<br>
{{ getSettings()->name }}
@endcomponent
