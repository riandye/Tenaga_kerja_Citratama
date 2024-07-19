<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class penerimaanAdmin extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    private $recruitment;
    public function __construct($recruitment)
    {
        $this->recruitment = $recruitment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable): array
    {
        $status = $this->recruitment->info_penerimaan;

        if ($status === 'diterima') {
            $title = 'Pelamar a/n'. $this->recruitment->user->info['name'] . ' diterima di ' . $this->recruitment->PerusahaanMitra->info['name'];
            $message = 'Pelamar a/n'.$this->recruitment->user->info['name'] . ' telah diterima di perusahaan ' . $this->recruitment->PerusahaanMitra->info['name'];
        } elseif ($status === 'ditolak') {
            $title = 'Pelamar a/n'. $this->recruitment->user->info['name'] . ' ditolak di ' . $this->recruitment->PerusahaanMitra->info['name'];
            $message = 'Pelamar a/n'. $this->recruitment->user->info['name'] . ' ditolak oleh perusahaan ' . $this->recruitment->PerusahaanMitra->info['name'];
        } else {
            $title = 'Status Rekrutmen Tidak Dikenal';
            $message = 'Status rekrutmen untuk ' . $this->recruitment->user->info['name'] . ' tidak diketahui.';
        }
        return [
            'mitra' => $this->recruitment->ID_mitra,
            'user' => $this->recruitment->ID_user,
            'title' => $title,
            'message' => $message 
        ];
    }
}
