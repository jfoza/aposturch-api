<?php

namespace App\Features\Users\CustomerUsers\Jobs;

use App\Shared\Enums\EmailLinksEnum;
use App\Shared\Enums\QueueEnum;
use App\Shared\Mail\GeneratedCustomerUserMail;
use App\Features\Users\NewPasswordGenerations\DTO\NewPasswordGenerationsDTO;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class EmailGeneratedCustomerUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        private readonly NewPasswordGenerationsDTO $newPasswordGenerationsDTO
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
        $logo = env('APP_ADMIN_URL') . "/email/lalak-logo-email.png";

        Mail::to($this->newPasswordGenerationsDTO->email)
            ->send(
                new GeneratedCustomerUserMail(
                    $this->newPasswordGenerationsDTO,
                    EmailLinksEnum::getLinks()
                )
            );
    }
}
