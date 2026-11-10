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
        if (! $this->app->runningInConsole()) {
            @set_time_limit(120);
        }

        VerifyEmail::toMailUsing(function (object $notifiable, string $url): MailMessage {
            return (new MailMessage)
                ->subject('Verifikasi email akun TeamMate kamu')
                ->greeting('Halo, '.$notifiable->full_name.'!')
                ->line('Selamat! Data diri dan dokumen KTM yang kamu daftarkan di TeamMate telah diperiksa dan dinyatakan VALID oleh Admin.')
                ->line('Klik tombol di bawah ini untuk memverifikasi alamat email kamu dan langsung masuk ke Home Page TeamMate.')
                ->action('Verifikasi Email', $url)
                ->line('Link ini berlaku selama '.config('auth.verification.expire', 60).' menit.')
                ->line('Jika kamu tidak merasa membuat akun, abaikan email ini.')
                ->salutation('Salam, Tim Admin TeamMate');
        });
    }
}
