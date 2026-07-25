@component('mail::message')
# Verify Your Email Address

Hello!

Please click the button below to verify your email address.

@component('mail::button', ['url' => $verificationUrl])
Verify Email Address
@endcomponent

If you did not create an account, no further action is required.

Regards,<br>
**SkyLine Airlines**
@endcomponent