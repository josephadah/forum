@component('mail::message')
# One Last Step

Please confirm your email to prove you are human. 

@component('mail::button', ['url' => url('/register/confirm?token=' . $user->confirmation_token)])
Click To Confirm Now
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
