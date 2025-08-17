<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class CustomVerifyEmail extends BaseVerifyEmail
{
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Verifikasi Email Anda')
            ->greeting('Halo!')
            ->line('Klik tombol di bawah untuk memverifikasi email Anda.')
            ->action('Verifikasi Email', $this->verificationUrl($notifiable))
            ->line('Jika Anda tidak mendaftar, abaikan email ini.')
            ->salutation('Regards, putraservice.com');
    }
}
