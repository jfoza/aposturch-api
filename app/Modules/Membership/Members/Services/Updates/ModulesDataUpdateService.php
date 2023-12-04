<?php

namespace App\Modules\Membership\Members\Services\Updates;

use App\Base\Exceptions\EnvironmentException;
use App\Base\Traits\AutomaticLogoutTrait;
use App\Exceptions\AppException;
use App\Features\Module\Modules\Contracts\ModulesRepositoryInterface;
use App\Features\Module\Modules\Validations\ModulesValidations;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\Contracts\Updates\ModulesDataUpdateServiceInterface;
use App\Modules\Membership\Members\Responses\UpdateMemberResponse;
use App\Modules\Membership\Members\Services\MembersBaseService;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;
use Exception;

class ModulesDataUpdateService extends MembersBaseService implements ModulesDataUpdateServiceInterface
{
    use AutomaticLogoutTrait;

    private string $userId;
    private array $modulesId;
    private mixed $userMember;

    public function __construct(
        protected MembersRepositoryInterface $membersRepository,
        private readonly UsersRepositoryInterface   $usersRepository,
        private readonly ModulesRepositoryInterface $modulesRepository,
        private readonly UpdateMemberResponse       $updateMemberResponse,
    ) {
        parent::__construct($this->membersRepository);
    }

    /**
     * @throws AppException
     */
    public function execute(string $userId, array $modulesId): UpdateMemberResponse
    {
        $this->userId = $userId;
        $this->modulesId = $modulesId;

        $policy = $this->getPolicy();

        return match (true)
        {
            $policy->haveRule(RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER_UPDATE->value)
                => $this->updateByAdminMaster(),

            $policy->haveRule(RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_UPDATE->value)
                => $this->updateByAdminChurch(),

            default => $policy->dispatchForbiddenError(),
        };
    }

    /**
     * @throws AppException
     */
    private function updateByAdminMaster(): UpdateMemberResponse
    {
        $this->userMember = $this->findOrFail($this->userId);

        $this->handleGeneralValidations();

        return $this->updateMemberData();
    }

    /**
     * @throws AppException
     */
    private function updateByAdminChurch(): UpdateMemberResponse
    {
        $this->userMember = $this->findOrFailWithHierarchy(
            $this->userId,
            ProfileUniqueNameEnum::ADMIN_CHURCH->value,
        );

        $this->handleGeneralValidations();

        return $this->updateMemberData();
    }

    /**
     * @throws AppException
     */
    private function handleGeneralValidations(): void
    {
        ModulesValidations::validateModulesId(
            $this->modulesId,
            $this->modulesRepository
        );
    }

    /**
     * @return UpdateMemberResponse
     * @throws AppException
     */
    public function updateMemberData(): UpdateMemberResponse
    {
        Transaction::beginTransaction();

        try
        {
            $userId = $this->userMember->user_id;

            $this->usersRepository->saveModules(
                $userId,
                $this->modulesId
            );

            $this->updateMemberResponse->id        = $this->userId;
            $this->updateMemberResponse->modulesId = $this->modulesId;

            $this->invalidateSessionsUser($this->userId);

            Transaction::commit();

            return $this->updateMemberResponse;
        }
        catch (Exception $e)
        {
            Transaction::rollback();

            EnvironmentException::dispatchException($e);
        }
    }
}
