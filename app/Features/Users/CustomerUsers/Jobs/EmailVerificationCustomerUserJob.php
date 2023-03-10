<?php

namespace App\Features\Users\CustomerUsers\Jobs;

use App\Shared\Enums\EmailLinksEnum;
use App\Shared\Enums\QueueEnum;
use App\Shared\Mail\EmailVerificationCustomerUserMail;
use App\Features\Users\EmailVerification\DTO\EmailVerificationDTO;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class EmailVerificationCustomerUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        private readonly EmailVerificationDTO $emailVerificationDTO
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
        Mail::to($this->emailVerificationDTO->email)
            ->send(
                new EmailVerificationCustomerUserMail(
                    $this->emailVerificationDTO,
                    EmailLinksEnum::getLinks()
                )
            );
    }
}
