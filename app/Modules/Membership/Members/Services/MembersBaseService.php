<?php

namespace App\Modules\Membership\Members\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\AuthenticatedService;
use App\Features\Base\Validations\ProfileHierarchyValidation;
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

        $userProfile = [$member->profile_unique_name];

        match ($profileForValidation)
        {
            ProfileUniqueNameEnum::ADMIN_CHURCH->value =>
                ProfileHierarchyValidation::adminChurchInListingsValidation($userProfile),

            ProfileUniqueNameEnum::ADMIN_MODULE->value =>
                ProfileHierarchyValidation::adminModuleInListingsValidation($userProfile),

            ProfileUniqueNameEnum::ASSISTANT->value =>
                ProfileHierarchyValidation::assistantInListingsValidation($userProfile),

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

        $userProfile = [$member->profile_unique_name];

        match ($profileForValidation)
        {
            ProfileUniqueNameEnum::ADMIN_CHURCH->value =>
                ProfileHierarchyValidation::handleBaseValidationInListings(
                    $userProfile,
                    [
                        ProfileUniqueNameEnum::ADMIN_MODULE->value,
                        ProfileUniqueNameEnum::ASSISTANT->value,
                        ProfileUniqueNameEnum::MEMBER->value,
                    ]
                ),

            ProfileUniqueNameEnum::ADMIN_MODULE->value =>
                ProfileHierarchyValidation::handleBaseValidationInListings(
                    $userProfile,
                    [
                        ProfileUniqueNameEnum::ASSISTANT->value,
                        ProfileUniqueNameEnum::MEMBER->value,
                    ]
                ),

            ProfileUniqueNameEnum::ASSISTANT->value =>
                ProfileHierarchyValidation::handleBaseValidationInListings(
                    $userProfile,
                    [
                        ProfileUniqueNameEnum::MEMBER->value,
                    ]
                ),

            default => ProfileHierarchyValidation::dispatchExceptionProfileNotAllowed(),
        };

        $churchesId = $member->church->pluck(Church::ID)->toArray();
        $this->canAccessTheChurch($churchesId, MessagesEnum::NO_ACCESS_TO_CHURCH_MEMBERS->value);

        return $member;
    }
}
