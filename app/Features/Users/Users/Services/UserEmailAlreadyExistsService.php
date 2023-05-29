<?php

namespace App\Features\Users\Users\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\Service;
use App\Features\Users\Users\Contracts\UserEmailAlreadyExistsServiceInterface;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use Symfony\Component\HttpFoundation\Response;

class UserEmailAlreadyExistsService extends Service implements UserEmailAlreadyExistsServiceInterface
{
    public function __construct(
        private readonly UsersRepositoryInterface $usersRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(string $email): void
    {
        $this->getPolicy()->havePermission(RulesEnum::USERS_EMAIL_ALREADY_EXISTS_VERIFICATION_VIEW->value);

        if(!empty($this->usersRepository->findByEmail($email)))
        {
            throw new AppException(
                MessagesEnum::EMAIL_ALREADY_EXISTS,
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}
