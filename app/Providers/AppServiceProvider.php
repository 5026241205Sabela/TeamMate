<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        VerifyEmail::toMailUsing(function (object $notifiable, string $url): MailMessage {
            return (new MailMessage)
                ->subject('Verifikasi email akun TeamMate kamu')
                ->greeting('Halo, '.$notifiable->full_name.'!')
                ->line('Terima kasih sudah mendaftar di TeamMate. Klik tombol di bawah untuk memverifikasi alamat email kamu.')
                ->action('Verifikasi Email', $url)
                ->line('Link ini berlaku selama '.config('auth.verification.expire', 60).' menit.')
                ->line('Jika kamu tidak merasa membuat akun, abaikan email ini.')
                ->salutation('Salam, tim TeamMate');
        });
    }
}
