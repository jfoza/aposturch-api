<?php

namespace App\Features\Users\ForgotPassword\Jobs;

use App\Shared\Enums\EmailLinksEnum;
use App\Shared\Enums\QueueEnum;
use App\Shared\Helpers\Helpers;
use App\Shared\Mail\ForgotPasswordMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailForgotPasswordJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        private readonly string $email,
        private readonly string $code
    )
    {
        $this->onQueue(QueueEnum::EMAIL->value);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $forgotPasswordLink = Helpers::getAppWebUrl('esqueci-minha-senha/nova-senha')."/{$this->code}";

        Mail::to($this->email)->send(new ForgotPasswordMail(
            EmailLinksEnum::getLinks(),
            $forgotPasswordLink
        ));
    }
}
