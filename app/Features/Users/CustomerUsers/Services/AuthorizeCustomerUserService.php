<?php

namespace App\Features\Users\CustomerUsers\Services;

use App\Exceptions\AppException;
use App\Features\Users\CustomerUsers\Contracts\CustomerUsersRepositoryInterface;
use App\Features\Users\CustomerUsers\Services\Utils\CustomerUsersValidationsService;
use App\Features\Users\EmailVerification\Contracts\EmailVerificationRepositoryInterface;
use App\Features\Users\EmailVerification\Services\Utils\EmailVerificationValidationsService;

class AuthorizeCustomerUserService
{
    public function __construct(
        private readonly CustomerUsersRepositoryInterface $customerUsersRepository,
        private readonly EmailVerificationRepositoryInterface $emailVerificationRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(string $userId, string $code): void
    {
        $customerUser = CustomerUsersValidationsService::customerUserIdExists(
            $this->customerUsersRepository,
            $userId
        );

        CustomerUsersValidationsService::isEmailAlreadyVerify(
            $customerUser->verified_email
        );

        EmailVerificationValidationsService::isValid(
            $this->emailVerificationRepository,
            $userId,
            $code
        );

        $this->customerUsersRepository->authorizeCustomerUser($customerUser->id);
    }
}
