@component('mail::message')
# Reset Password

your verification code to Reset your Password is : 
## {{$token}}


{{-- @component('mail::button', ['url' => $token])
Reset
@endcomponent --}}

<br>
Thanks,<br>
{{ config('app.name') }}
@endcomponent
