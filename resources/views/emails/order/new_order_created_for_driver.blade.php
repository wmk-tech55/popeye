@component('mail::message')

{{-- @component('mail::promotion')
<img src="{{ asset('/assets/img/logo.png') }}" alt="{{ getSettings()->name }}">
@endcomponent --}}

# @lang($subject)

@lang($message)
<br>
<h5>@lang("main.order_details") :</h5>

<strong>
@lang("main.description") :   
</strong>
{{$description}}
<br/>
<strong>
@lang("main.address") :   
</strong>
{{$address}}

@lang('notifications.thanks'),<br>
{{ getSettings()->name }}
@endcomponent