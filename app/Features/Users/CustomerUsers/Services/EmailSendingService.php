<?php

namespace App\Features\Users\CustomerUsers\Services;

use App\Shared\Helpers\Helpers;
use App\Shared\Helpers\RandomStringHelper;
use App\Features\Users\CustomerUsers\Jobs\EmailVerificationCustomerUserJob;
use App\Features\Users\EmailVerification\Contracts\EmailVerificationRepositoryInterface;
use App\Features\Users\EmailVerification\DTO\EmailVerificationDTO;

class EmailSendingService
{
    public function __construct(
        private readonly EmailVerificationRepositoryInterface $emailVerificationRepository,
        private readonly EmailVerificationDTO $emailVerificationDTO,
    ) {}

    public function execute(string $userId, string $email): void
    {
        $currentDate = Helpers::getCurrentTimestampCarbon();

        $this->emailVerificationDTO->userId   = $userId;
        $this->emailVerificationDTO->email    = $email;
        $this->emailVerificationDTO->code     = RandomStringHelper::numericGenerate(6);
        $this->emailVerificationDTO->validate = $currentDate->addDay()->format('Y-m-d H:i:s');

        $this->emailVerificationRepository->create($this->emailVerificationDTO);

        EmailVerificationCustomerUserJob::dispatch($this->emailVerificationDTO);
    }
}
