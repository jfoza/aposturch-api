<?php

namespace App\Features\Users\EmailVerification\Services;

use App\Shared\Helpers\Helpers;
use App\Features\Users\EmailVerification\Contracts\EmailVerificationRepositoryInterface;
use App\Features\Users\EmailVerification\DTO\EmailVerificationDTO;

class InvalidateEmailVerificationCodeService
{
    public function __construct(
        private readonly EmailVerificationRepositoryInterface $emailVerificationRepository,
        private readonly EmailVerificationDTO $emailVerificationDTO
    ) {}

    public function execute(string $code): void
    {
        $currentDate = Helpers::getCurrentTimestampCarbon();

        $this->emailVerificationDTO->code = $code;
        $this->emailVerificationDTO->active = false;
        $this->emailVerificationDTO->validate = $currentDate->subDays(3)->format('Y-m-d H:i:s');

        $this->emailVerificationRepository->invalidateCode($this->emailVerificationDTO);
    }
}
