<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountRejectedNotification extends Notification
{
    use Queueable;

    public function __construct(public string $reason) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Build the rejection mail message sent to the applicant.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pendaftaran Akun TeamMate Ditolak — Data Tidak Valid')
            ->greeting('Halo, '.($notifiable->full_name ?? 'Mahasiswa').'!')
            ->line('Terima kasih telah mendaftar di TeamMate. Setelah Admin memeriksa data diri dan dokumen KTM yang kamu unggah, mohon maaf pendaftaran akun kamu belum dapat disetujui karena data dinyatakan tidak valid.')
            ->line('Catatan dari Admin: "'.$this->reason.'"')
            ->line('Silakan lakukan pendaftaran ulang dengan memastikan seluruh data diri (Nama Lengkap, Universitas, NIM) dan foto KTM terlihat jelas serta sesuai.')
            ->action('Daftar Ulang di TeamMate', route('landing', ['tab' => 'register']))
            ->salutation('Salam, Tim Admin TeamMate');
    }
}
