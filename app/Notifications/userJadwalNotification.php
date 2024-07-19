<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class userJadwalNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    private $jadwal;
    public function __construct($jadwal)
    {
        $this->jadwal = $jadwal;
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
        return [
            'mitra' => $this->jadwal->ID_mitra,
            'title' => 'jadwal untuk mengikuti wawancara',
            'message' => 'Berikut kami sampaikan mengenai jadwal wawancara anda, tanggal '. $this->jadwal->tanggal . ' di '. $this->jadwal->tempat . ' jam ' . $this->jadwal->jam ,
        ];
    }
}
