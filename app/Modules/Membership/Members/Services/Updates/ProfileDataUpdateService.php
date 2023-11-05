<?php

namespace App\Modules\Membership\Members\Services\Updates;

use App\Base\Traits\AutomaticLogoutTrait;
use App\Base\Traits\EnvironmentException;
use App\Base\Validations\ProfileHierarchyValidation;
use App\Exceptions\AppException;
use App\Features\Users\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\Validations\UsersValidations;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\Contracts\Updates\ProfileDataUpdateServiceInterface;
use App\Modules\Membership\Members\Responses\UpdateMemberResponse;
use App\Modules\Membership\Members\Services\MembersBaseService;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;
use Exception;

class ProfileDataUpdateService extends MembersBaseService implements ProfileDataUpdateServiceInterface
{
    use AutomaticLogoutTrait;

    private string $userId;
    private string $profileId;
    private mixed $profile;

    public function __construct(
        protected MembersRepositoryInterface $membersRepository,
        private readonly UsersRepositoryInterface    $usersRepository,
        private readonly ProfilesRepositoryInterface $profilesRepository,
        private readonly UpdateMemberResponse        $updateMemberResponse,
    ) {
        parent::__construct($this->membersRepository);
    }

    /**
     * @throws AppException
     */
    public function execute(string $userId, string $profileId): UpdateMemberResponse
    {
        $this->userId = $userId;
        $this->profileId = $profileId;

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
        $this->findOrFail($this->userId);

        $this->handleGeneralValidations();

        ProfileHierarchyValidation::adminChurchInPersistenceValidation([$this->profile->unique_name]);

        return $this->updateMemberData();
    }

    /**
     * @throws AppException
     */
    private function updateByAdminChurch(): UpdateMemberResponse
    {
        $this->findOrFailWithHierarchyInUpdate(
            $this->userId,
            ProfileUniqueNameEnum::ADMIN_CHURCH->value,
        );

        $this->handleGeneralValidations();

        ProfileHierarchyValidation::adminChurchInPersistenceValidation([$this->profile->unique_name]);

        return $this->updateMemberData();
    }

    /**
     * @throws AppException
     */
    private function handleGeneralValidations(): void
    {
        $this->profile = UsersValidations::returnProfileExists(
            $this->profilesRepository,
            $this->profileId
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
            $this->usersRepository->saveProfiles($this->userId, [$this->profileId]);

            $this->updateMemberResponse->id        = $this->userId;
            $this->updateMemberResponse->profileId = $this->profileId;

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
