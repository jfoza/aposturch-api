<?php

namespace App\Features\Users\CustomerUsers\Services;

use App\Shared\Enums\MessagesEnum;
use App\Exceptions\AppException;
use App\Features\Users\CustomerUsers\Contracts\CustomerUsersRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

class ShowCustomerService
{
    public function __construct(
        private readonly CustomerUsersRepositoryInterface $customerUsersRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(string $customerUserId)
    {
        $customerUser = $this->customerUsersRepository->findById($customerUserId);

        if(empty($customerUser)) {
            throw new AppException(
                MessagesEnum::USER_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        return $customerUser;
    }
}
