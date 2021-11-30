<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegistrationNotification extends Notification
{
    use Queueable;

    protected $credentials;
    protected $url;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($credentials, $url)
    {
        $this->credentials = $credentials;
        $this->url = $url;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('Registration successful!')
            ->line('Your credentials are:')
            ->line('email: '.$this->credentials['email'])
            ->line('password: '.$this->credentials['password'])
            ->line("If you wish to reset your password, please click button below")
            ->action('Reset password', $this->url)
            ->line('Thank you for using our application!')
            ;
            
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
