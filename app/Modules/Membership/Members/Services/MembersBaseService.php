<?php

namespace App\Modules\Membership\Members\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\AuthenticatedService;
use App\Features\Base\Validations\ProfileHierarchyValidation;
use App\Features\Module\Modules\Models\Module;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Modules\Membership\Church\Models\Church;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Shared\Enums\MessagesEnum;
use Symfony\Component\HttpFoundation\Response;

class MembersBaseService extends AuthenticatedService
{
    public function __construct(
        protected MembersRepositoryInterface $membersRepository,
    ) {}

    /**
     * @throws AppException
     */
    protected function findOrFail(string $userId): object
    {
        if(!$member = $this->membersRepository->findByUserId($userId))
        {
            throw new AppException(
                MessagesEnum::USER_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        return $member;
    }

    /**
     * @throws AppException
     */
    protected function findOrFailWithHierarchy(
        string $userId,
        string $profileForValidation,
    ): object
    {
        $member = $this->findOrFail($userId);

        match ($profileForValidation)
        {
            ProfileUniqueNameEnum::ADMIN_CHURCH->value => $this->validateAdminChurchInListings($member),

            ProfileUniqueNameEnum::ADMIN_MODULE->value => $this->validateAdminModuleInListings($member),

            ProfileUniqueNameEnum::ASSISTANT->value    => $this->validateAssistantInListings($member),

            default => ProfileHierarchyValidation::dispatchExceptionProfileNotAllowed(),
        };

        $churchesId = $member->church->pluck(Church::ID)->toArray();
        $this->canAccessTheChurch($churchesId, MessagesEnum::NO_ACCESS_TO_CHURCH_MEMBERS->value);

        return $member;
    }

    /**
     * @throws AppException
     */
    protected function findOrFailWithHierarchyInUpdate(
        string $userId,
        string $profileForValidation,
    ): object
    {
        $member = $this->findOrFail($userId);

        match ($profileForValidation)
        {
            ProfileUniqueNameEnum::ADMIN_CHURCH->value => $this->validateAdminChurchInUpdates($member),

            ProfileUniqueNameEnum::ADMIN_MODULE->value => $this->validateAdminModuleInUpdates($member),

            ProfileUniqueNameEnum::ASSISTANT->value    => $this->validateAssistantInUpdates($member),

            default => ProfileHierarchyValidation::dispatchExceptionProfileNotAllowed(),
        };

        $churchesId = $member->church->pluck(Church::ID)->toArray();
        $this->canAccessTheChurch($churchesId, MessagesEnum::NO_ACCESS_TO_CHURCH_MEMBERS->value);

        return $member;
    }

    /**
     * @throws AppException
     */
    protected function validateAdminChurchInListings(object $member): void
    {
        $userProfile = [$member->profile_unique_name];

        ProfileHierarchyValidation::adminChurchInListingsValidation($userProfile);
    }

    /**
     * @throws AppException
     */
    protected function validateAdminModuleInListings(object $member): void
    {
        $userProfile = [$member->profile_unique_name];

        ProfileHierarchyValidation::adminModuleInListingsValidation($userProfile);

        if(!$modulesId = collect($member->user->module)->pluck(Module::ID)->toArray())
        {
            throw new AppException(
                MessagesEnum::USER_HAS_NO_LINKED_MODULES,
                Response::HTTP_FORBIDDEN
            );
        }

        $this->canAccessModules($modulesId);
    }

    /**
     * @throws AppException
     */
    protected function validateAssistantInListings(object $member): void
    {
        $userProfile = [$member->profile_unique_name];

        ProfileHierarchyValidation::assistantInListingsValidation($userProfile);

        if(!$modulesId = collect($member->user->module)->pluck(Module::ID)->toArray())
        {
            throw new AppException(
                MessagesEnum::USER_HAS_NO_LINKED_MODULES,
                Response::HTTP_FORBIDDEN
            );
        }

        $this->canAccessModules($modulesId);
    }

    /**
     * @throws AppException
     */
    protected function validateAdminChurchInUpdates(object $member): void
    {
        $userProfile = [$member->profile_unique_name];

        ProfileHierarchyValidation::handleBaseValidationInListings(
            $userProfile,
            [
                ProfileUniqueNameEnum::ADMIN_MODULE->value,
                ProfileUniqueNameEnum::ASSISTANT->value,
                ProfileUniqueNameEnum::MEMBER->value,
            ]
        );
    }

    /**
     * @throws AppException
     */
    protected function validateAdminModuleInUpdates(object $member): void
    {
        $userProfile = [$member->profile_unique_name];

        ProfileHierarchyValidation::handleBaseValidationInListings(
            $userProfile,
            [
                ProfileUniqueNameEnum::ASSISTANT->value,
                ProfileUniqueNameEnum::MEMBER->value,
            ]
        );

        if(!$modulesId = collect($member->user->module)->pluck(Module::ID)->toArray())
        {
            throw new AppException(
                MessagesEnum::USER_HAS_NO_LINKED_MODULES,
                Response::HTTP_FORBIDDEN
            );
        }

        $this->canAccessModules($modulesId);
    }

    /**
     * @throws AppException
     */
    protected function validateAssistantInUpdates(object $member): void
    {
        $userProfile = [$member->profile_unique_name];

        ProfileHierarchyValidation::handleBaseValidationInListings(
            $userProfile,
            [
                ProfileUniqueNameEnum::MEMBER->value,
            ]
        );

        if(!$modulesId = collect($member->user->module)->pluck(Module::ID)->toArray())
        {
            throw new AppException(
                MessagesEnum::USER_HAS_NO_LINKED_MODULES,
                Response::HTTP_FORBIDDEN
            );
        }

        $this->canAccessModules($modulesId);
    }
}
