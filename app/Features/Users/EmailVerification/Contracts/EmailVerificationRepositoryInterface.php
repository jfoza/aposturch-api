<?php

namespace App\Features\Users\EmailVerification\Contracts;

use App\Features\Users\EmailVerification\DTO\EmailVerificationDTO;

interface EmailVerificationRepositoryInterface
{
    public function findByUserIdAndCode(string $userId, $code);
    public function invalidateCode(EmailVerificationDTO $emailVerificationDTO);
    public function create(EmailVerificationDTO $emailVerificationDTO);
}
