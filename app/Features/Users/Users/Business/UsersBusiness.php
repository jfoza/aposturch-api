<?php

namespace App\Features\Users\Users\Business;

use App\Shared\Enums\RulesEnum;
use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\Users\Users\Contracts\UsersBusinessInterface;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;

class UsersBusiness
    extends Business
    implements UsersBusinessInterface
{
    public function __construct(
        private readonly UsersRepositoryInterface $usersRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function findById(string $id)
    {
        $this->getPolicy()->havePermission(RulesEnum::USERS_VIEW->value);

        return $this->usersRepository->findById($id);
    }
}
