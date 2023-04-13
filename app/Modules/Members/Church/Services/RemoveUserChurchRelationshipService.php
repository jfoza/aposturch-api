<?php

namespace App\Modules\Members\Church\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\Service;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\Validations\UsersValidationsService;
use App\Modules\Members\Church\Contracts\RemoveUserChurchRelationshipServiceInterface;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class RemoveUserChurchRelationshipService extends Service implements RemoveUserChurchRelationshipServiceInterface
{
    public function __construct(
        private readonly UsersRepositoryInterface $usersRepository
    ) {}

    /**
     * @throws AppException
     */
    public function execute(string $userId)
    {
        $this->getPolicy()->havePermission(RulesEnum::MEMBERS_MODULE_CHURCH_USER_RELATIONSHIP_DELETE->value);

        UsersValidationsService::validateUserExistsById(
            $userId,
            $this->usersRepository
        );

        Transaction::beginTransaction();

        try
        {
            $this->usersRepository->removeChurchRelationship($userId);

            Transaction::commit();
        }
        catch (\Exception $e)
        {
            Transaction::rollback();

            $this->dispatchException($e);
        }
    }
}
