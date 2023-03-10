<?php

namespace App\Shared\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    private array $data;

    public function __construct(array $links, string $forgotPasswordLink)
    {
        $this->data = [
            'links' => $links,
            'forgotPasswordLink' => $forgotPasswordLink
        ];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Esqueci minha senha')->markdown('emails.forgot-password', $this->data);
    }
}
