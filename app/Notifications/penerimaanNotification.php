<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class penerimaanNotification extends Notification
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
    public function via(object $notifiable): array
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
    public function toArray(object $notifiable): array
    {
        return [
            'mitra' => $this->recruitment->ID_mitra,
            'user' => $this->recruitment->ID_user,
            'title' => 'pelamar a/n ' . $this->recruitment->user->info['name'] . ' diterima kerja oleh ' . $this->recruitment->PerusahaanMitra->info['name'],
            'message' => $this->recruitment->User->info['name'] . ' diterima di ' . $this->recruitment->PerusahaanMitra->info['name'],
        ];
    }
}
