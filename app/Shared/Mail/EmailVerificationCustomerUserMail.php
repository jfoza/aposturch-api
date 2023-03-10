<?php

namespace App\Shared\Mail;

use App\Features\Users\EmailVerification\DTO\EmailVerificationDTO;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailVerificationCustomerUserMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    private array $data;

    public function __construct(
        EmailVerificationDTO $emailVerificationDTO,
        array $links
    )
    {
        $this->data = [
            'links' => $links,
            'code'  => $emailVerificationDTO->code,
        ];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        return $this->subject('Novo Usuário Lalak Doceria')->markdown('emails.email-verification-customer-user', $this->data);
    }
}
