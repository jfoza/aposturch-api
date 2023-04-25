<?php

namespace App\Modules\Members\Church\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\Service;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\Validations\UsersValidations;
use App\Modules\Members\Church\Contracts\RemoveUserChurchRelationshipServiceInterface;
use App\Modules\Members\Church\Models\Church;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class RemoveMemberChurchRelationshipService extends Service implements RemoveUserChurchRelationshipServiceInterface
{
    private string $userId;
    private object $churchUserPayload;
    public function __construct(
        private readonly UsersRepositoryInterface $usersRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(string $userId)
    {
        $this->userId = $userId;

        $policy = $this->getPolicy();

        match (true)
        {
            $policy->haveRule(RulesEnum::MEMBERS_MODULE_CHURCH_ADMIN_MASTER_USER_RELATIONSHIP_DELETE->value)
                => $this->deleteByAdminMaster(),

            $policy->haveRule(RulesEnum::MEMBERS_MODULE_CHURCH_ADMIN_CHURCH_USER_RELATIONSHIP_DELETE->value)
                => $this->deleteByAdminChurch(),

            default => $policy->dispatchErrorForbidden()
        };
    }

    /**
     * @throws AppException
     */
    private function deleteByAdminMaster()
    {
        $this->handleValidations();

        $this->baseDeleteOperation();
    }

    /**
     * @throws AppException
     */
    private function deleteByAdminChurch()
    {
        $this->handleValidations();

        $this->userHasChurch(
            Church::ID,
            $this->churchUserPayload->id
        );

        $this->baseDeleteOperation();
    }

    /**
     * @throws AppException
     */
    private function handleValidations()
    {
        $user = UsersValidations::validateUserExistsByIdAndHasChurch(
            $this->userId,
            $this->usersRepository
        );

        $this->churchUserPayload = $user->church->first();
    }

    /**
     * @throws AppException
     */
    private function baseDeleteOperation()
    {
        Transaction::beginTransaction();

        try
        {
            $this->usersRepository->removeChurchRelationship($this->userId, $this->churchUserPayload->id);

            Transaction::commit();
        }
        catch (\Exception $e)
        {
            Transaction::rollback();

            $this->dispatchException($e);
        }
    }
}
