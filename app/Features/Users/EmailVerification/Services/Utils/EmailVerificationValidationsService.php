<?php

namespace App\Features\Users\EmailVerification\Services\Utils;

use App\Shared\Enums\MessagesEnum;
use App\Exceptions\AppException;
use App\Shared\Helpers\Helpers;
use App\Features\Users\EmailVerification\Contracts\EmailVerificationRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

class EmailVerificationValidationsService
{
    /**
     * @throws AppException
     */
    public static function isValid(
        EmailVerificationRepositoryInterface $emailVerificationRepository,
        string $userId,
        string $code
    ): void
    {
        if(!$emailVerification = $emailVerificationRepository->findByUserIdAndCode($userId, $code))
        {
            throw new AppException(
                MessagesEnum::CODE_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        $currentDate = Helpers::getCurrentTimestampCarbon();

        if(!$currentDate->lt($emailVerification->validate)) {
            throw new AppException(
                MessagesEnum::INVALID_CODE,
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}
