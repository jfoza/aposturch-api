<?php

namespace App\Features\Users\EmailVerification\Infra\Repositories;

use App\Features\Users\EmailVerification\Contracts\EmailVerificationRepositoryInterface;
use App\Features\Users\EmailVerification\DTO\EmailVerificationDTO;
use App\Features\Users\EmailVerification\Infra\Models\EmailVerification;

class EmailVerificationRepository implements EmailVerificationRepositoryInterface
{
    public function findByUserIdAndCode(string $userId, $code)
    {
        return EmailVerification::where([
                EmailVerification::USER_ID => $userId,
                EmailVerification::CODE => $code,
                EmailVerification::ACTIVE => true,
            ])
            ->first();
    }

    public function create(EmailVerificationDTO $emailVerificationDTO)
    {
        return EmailVerification::create([
            EmailVerification::USER_ID  => $emailVerificationDTO->userId,
            EmailVerification::CODE     => $emailVerificationDTO->code,
            EmailVerification::VALIDATE => $emailVerificationDTO->validate,
        ]);
    }

    public function invalidateCode(EmailVerificationDTO $emailVerificationDTO)
    {
        EmailVerification::where(
            EmailVerification::CODE, $emailVerificationDTO->code
        )
        ->update([
            EmailVerification::ACTIVE => $emailVerificationDTO->active,
            EmailVerification::VALIDATE => $emailVerificationDTO->validate,
        ]);
    }
}
