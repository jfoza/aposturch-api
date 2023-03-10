<?php

namespace App\Shared\Mail;

use App\Features\Support\UserSupport\DTO\UserSupportDTO;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserSupportMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    private array $data;

    public function __construct(
        private readonly UserSupportDTO $userSupportDTO,
        private readonly array $links
    )
    {
        $this->data = [
            'name'    => $userSupportDTO->name,
            'email'   => $userSupportDTO->email,
            'phone'   => $userSupportDTO->phoneMask,
            'message' => $userSupportDTO->message,
            'links'   => $this->links
        ];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Ajuda e Suporte')->markdown('emails.support', $this->data);
    }
}
