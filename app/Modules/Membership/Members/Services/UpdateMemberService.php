<?php

namespace App\Modules\Membership\Members\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\Service;
use App\Features\Users\AdminUsers\Validations\AllowedProfilesValidations;
use App\Features\Users\Users\DTO\UserDTO;
use App\Modules\Membership\Church\Validations\ChurchValidations;
use App\Modules\Membership\Members\Contracts\UpdateMemberServiceInterface;
use App\Modules\Membership\Members\Responses\UpdateMemberResponse;
use App\Modules\Membership\Members\Traits\BaseOperationsTrait;
use App\Modules\Membership\Members\Types\OperationsType;
use App\Modules\Membership\Members\Validations\MembersValidations;
use App\Shared\Enums\RulesEnum;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;

class UpdateMemberService extends Service implements UpdateMemberServiceInterface
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
    public function execute(UserDTO $userDTO): UpdateMemberResponse
    {
        $policy = $this->getPolicy();

        $this->userDTO = $userDTO;

        return match (true)
        {
            $policy->haveRule(RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER_UPDATE->value) => $this->updateByAdminMaster(),
            $policy->haveRule(RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_UPDATE->value) => $this->updateByAdminChurch(),
            $policy->haveRule(RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_UPDATE->value) => $this->updateByAdminModule(),
            $policy->haveRule(RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_UPDATE->value)    => $this->updateByAssistant(),

            default => $policy->dispatchErrorForbidden(),
        };
    }

    /**
     * @throws AppException
     */
    private function updateByAdminMaster(): UpdateMemberResponse
    {
        $this->membersValidations->handleValidationsInUpdate($this->userDTO);

        return $this->updateMemberData($this->userDTO, $this->operationsType);
    }

    /**
     * @throws AppException
     * @throws UserNotDefinedException
     */
    private function updateByAdminChurch(): UpdateMemberResponse
    {
        $this->membersValidations->handleValidationsInUpdate($this->userDTO);

        ChurchValidations::memberHasChurchById(
            $this->userDTO->member->churchId,
            $this->getChurchesUserMember()
        );

        AllowedProfilesValidations::validateAdminChurchProfile($this->userDTO->profile->unique_name);

        return $this->updateMemberData($this->userDTO, $this->operationsType);
    }

    /**
     * @throws AppException
     * @throws UserNotDefinedException
     */
    private function updateByAdminModule(): UpdateMemberResponse
    {
        $this->membersValidations->handleValidationsInUpdate($this->userDTO);

        ChurchValidations::memberHasChurchById(
            $this->userDTO->member->churchId,
            $this->getChurchesUserMember()
        );

        AllowedProfilesValidations::validateAdminModuleProfile($this->userDTO->profile->unique_name);

        return $this->updateMemberData($this->userDTO, $this->operationsType);
    }

    /**
     * @throws AppException|UserNotDefinedException
     */
    private function updateByAssistant(): UpdateMemberResponse
    {
        $this->membersValidations->handleValidationsInUpdate($this->userDTO);

        ChurchValidations::memberHasChurchById(
            $this->userDTO->member->churchId,
            $this->getChurchesUserMember()
        );

        AllowedProfilesValidations::validateAssistantProfile($this->userDTO->profile->unique_name);

        return $this->updateMemberData($this->userDTO, $this->operationsType);
    }
}
