<?php

namespace App\Modules\Membership\Members\Services\Updates;

use App\Exceptions\AppException;
use App\Features\Base\Services\AuthenticatedService;
use App\Features\Base\Traits\EnvironmentException;
use App\Features\Users\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\Validations\UsersValidations;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\Contracts\Updates\ProfileDataUpdateServiceInterface;
use App\Modules\Membership\Members\DTO\MembersFiltersDTO;
use App\Modules\Membership\Members\Responses\UpdateMemberResponse;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class ProfileDataUpdateService extends AuthenticatedService implements ProfileDataUpdateServiceInterface
{

    private string $userId;
    private string $profileId;

    public function __construct(
        private readonly UsersRepositoryInterface    $usersRepository,
        private readonly MembersRepositoryInterface  $membersRepository,
        private readonly ProfilesRepositoryInterface $profilesRepository,
        private readonly MembersFiltersDTO           $membersFiltersDTO,
        private readonly UpdateMemberResponse        $updateMemberResponse,
    ) {}

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
        $this->handleGeneralValidations();

        return $this->updateMemberData();
    }

    /**
     * @throws AppException
     */
    private function updateByAdminChurch(): UpdateMemberResponse
    {
        if($this->userPayloadIsEqualsAuthUser($this->userId))
        {
            throw new AppException(
                MessagesEnum::ACCESS_DENIED,
                Response::HTTP_FORBIDDEN
            );
        }

        $this->membersFiltersDTO->profileUniqueName = [
            ProfileUniqueNameEnum::ADMIN_MODULE->value,
            ProfileUniqueNameEnum::ASSISTANT->value,
            ProfileUniqueNameEnum::MEMBER->value,
        ];

        $this->membersFiltersDTO->churchesId = $this->getUserMemberChurchesId();

        $this->handleGeneralValidations();

        return $this->updateMemberData();
    }

    /**
     * @throws AppException
     */
    private function handleGeneralValidations()
    {
        if(!$this->membersRepository->findOneByFilters($this->userId, $this->membersFiltersDTO))
        {
            throw new AppException(
                MessagesEnum::USER_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        UsersValidations::returnProfileExists(
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
