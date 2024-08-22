<?php

namespace DigiFactory\FilamentWildcardLogin\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Queue\SerializesModels;

class WildcardLogin extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public string $loginUrl, public string $expiration)
    {
        //
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('filament-wildcard-login::wildcard-login.mail.subject', ['application' => config('app.name')]),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            htmlString: (new MailMessage)
                ->greeting(__('filament-wildcard-login::wildcard-login.mail.greeting'))
                ->line(__('filament-wildcard-login::wildcard-login.mail.intro', ['expiration' => $this->expiration]))
                ->action(__('filament-wildcard-login::wildcard-login.mail.button'), $this->loginUrl)
                ->salutation(__('filament-wildcard-login::wildcard-login.mail.salutation', ['application' => config('app.name')]))
                ->render(),
        );
    }
}
