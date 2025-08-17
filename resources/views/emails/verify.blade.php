@component('mail::message')
# Verifikasi Ulang Email Anda

Klik tombol di bawah untuk memverifikasi email Anda:

@component('mail::button', ['url' => $url])
Verifikasi Email
@endcomponent

Jika Anda tidak mendaftar, abaikan email ini.

Terima kasih,<br>
{{ config('app.name') }}
@endcomponent
