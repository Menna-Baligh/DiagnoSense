<?php

namespace App\Notifications;

use Ichtrojan\Otp\Otp;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\VonageMessage;
use Illuminate\Notifications\Notification;

class EmailVerificationNotification extends Notification
{
    use Queueable;

    private $otp;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        $this->otp = new Otp;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $channels = [];
        if ($notifiable->email) {
            $channels[] = 'mail';
        }
        if ($notifiable->phone) {
            $channels[] = 'vonage';
        }

        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $otp = $this->otp->generate($notifiable->email, 'numeric', 6, 10);

        return (new MailMessage)
            ->subject('Verify Your Email')
            ->greeting('Hello '.$notifiable->name)
            ->line('Use the following OTP to verify your email:')
            ->line('Your OTP is: **'.$otp->token.'**')
            ->line('This OTP will expire in 10 minutes.');
    }

    public function toVonage($notifiable)
    {
        $otp = $this->otp->generate($notifiable->phone, 'numeric', 6, 10);

        return (new VonageMessage)
            ->content("Hello {$notifiable->name}, Your OTP for email verification is: {$otp->token}. This OTP will expire in 10 minutes.");
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
