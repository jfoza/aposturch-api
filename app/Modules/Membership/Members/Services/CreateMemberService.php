<?php

namespace App\Modules\Membership\Members\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\Service;
use App\Features\Users\AdminUsers\Validations\AllowedProfilesValidations;
use App\Features\Users\Users\DTO\UserDTO;
use App\Modules\Membership\Church\Validations\ChurchValidations;
use App\Modules\Membership\Members\Contracts\CreateMemberServiceInterface;
use App\Modules\Membership\Members\Responses\InsertMemberResponse;
use App\Modules\Membership\Members\Traits\BaseOperationsTrait;
use App\Modules\Membership\Members\Types\OperationsType;
use App\Modules\Membership\Members\Validations\MembersValidations;
use App\Shared\Enums\RulesEnum;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;

class CreateMemberService extends Service implements CreateMemberServiceInterface
{
    use BaseOperationsTrait;

    private UserDTO $userDTO;

    public function __construct(
        private readonly OperationsType $operationsType,
        private readonly MembersValidations $membersValidations,
    ) {}

    /**
     * @throws AppException
     * @throws UserNotDefinedException
     */
    public function execute(UserDTO $userDTO): InsertMemberResponse
    {
        $policy = $this->getPolicy();

        $this->userDTO = $userDTO;

        return match (true)
        {
            $policy->haveRule(RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER_INSERT->value) => $this->createByAdminMaster(),
            $policy->haveRule(RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_INSERT->value) => $this->createByAdminChurch(),
            $policy->haveRule(RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_INSERT->value) => $this->createByAdminModule(),
            $policy->haveRule(RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_INSERT->value)    => $this->createByAssistant(),

            default => $policy->dispatchErrorForbidden(),
        };
    }

    /**
     * @throws AppException
     */
    private function createByAdminMaster(): InsertMemberResponse
    {
        $this->membersValidations->handleValidationsInCreate($this->userDTO);

        return $this->createNewMember($this->userDTO, $this->operationsType);
    }

    /**
     * @throws AppException
     * @throws UserNotDefinedException
     */
    private function createByAdminChurch(): InsertMemberResponse
    {
        $this->membersValidations->handleValidationsInCreate($this->userDTO);

        ChurchValidations::memberHasChurchById(
            $this->userDTO->member->churchId,
            $this->getChurchesUserMember()
        );

        AllowedProfilesValidations::validateAdminChurchProfile($this->userDTO->profile->unique_name);

        return $this->createNewMember($this->userDTO, $this->operationsType);
    }

    /**
     * @throws AppException
     * @throws UserNotDefinedException
     */
    private function createByAdminModule(): InsertMemberResponse
    {
        $this->membersValidations->handleValidationsInCreate($this->userDTO);

        ChurchValidations::memberHasChurchById(
            $this->userDTO->member->churchId,
            $this->getChurchesUserMember()
        );

        AllowedProfilesValidations::validateAdminModuleProfile($this->userDTO->profile->unique_name);

        return $this->createNewMember($this->userDTO, $this->operationsType);
    }

    /**
     * @throws AppException|UserNotDefinedException
     */
    private function createByAssistant(): InsertMemberResponse
    {
        $this->membersValidations->handleValidationsInCreate($this->userDTO);

        ChurchValidations::memberHasChurchById(
            $this->userDTO->member->churchId,
            $this->getChurchesUserMember()
        );

        AllowedProfilesValidations::validateAssistantProfile($this->userDTO->profile->unique_name);

        return $this->createNewMember($this->userDTO, $this->operationsType);
    }
}
