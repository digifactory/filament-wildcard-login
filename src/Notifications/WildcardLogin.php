<?php

namespace DigiFactory\FilamentWildcardLogin\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WildcardLogin extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(public string $loginUrl, public string $expiration)
    {
        //
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
            ->subject(__('filament-wildcard-login::wildcard-login.mail.subject', ['application' => config('app.name')]))
            ->greeting(__('filament-wildcard-login::wildcard-login.mail.greeting'))
            ->line(__('filament-wildcard-login::wildcard-login.mail.intro', ['expiration' => $this->expiration]))
            ->action(__('filament-wildcard-login::wildcard-login.mail.button'), $this->loginUrl)
            ->salutation(__('filament-wildcard-login::wildcard-login.mail.salutation', ['application' => config('app.name')]));
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
