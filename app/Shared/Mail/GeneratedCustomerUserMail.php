<?php

namespace App\Shared\Mail;

use App\Features\Users\NewPasswordGenerations\DTO\NewPasswordGenerationsDTO;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GeneratedCustomerUserMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    private array $data;

    public function __construct(
        NewPasswordGenerationsDTO $newPasswordGenerationsDTO,
        array $links
    )
    {
        $this->data = [
            'links' => $links,
            'email' => $newPasswordGenerationsDTO->email,
            'password' => $newPasswordGenerationsDTO->password,
        ];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        return $this->subject('Novo Usuário Lalak Doceria')->markdown('emails.generated-customer-user', $this->data);
    }
}
