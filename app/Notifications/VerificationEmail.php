<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerificationEmail extends Notification
{
    use Queueable;
    public $id,$expired;

    /**
     * Create a new notification instance.
     */
    public function __construct($id)
    {
        $this->id = $id;
        $this->expired = Carbon::now()->addMinutes(15);
    }

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
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Verify your email address')
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/verify-email/'.$this->id.'/'.$this->expired->timestamp))
            ->line('The link will expire in '.$this->expired->format('d-m-Y H:i:s'))
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
            //
        ];
    }
}
