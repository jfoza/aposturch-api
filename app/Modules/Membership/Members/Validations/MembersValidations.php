<?php

namespace App\Modules\Membership\Members\Validations;

use App\Exceptions\AppException;
use App\Features\Users\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\Validations\UsersValidations;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\DTO\MembersFiltersDTO;
use App\Shared\Enums\MessagesEnum;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class MembersValidations
{
    /**
     * @throws AppException
     */
    public static function profileIsValid(
        string $profileId,
        ProfilesRepositoryInterface $profilesRepository
    )
    {
        $profile = UsersValidations::returnProfileExists($profilesRepository, $profileId);

        $allowedProfiles = [
            ProfileUniqueNameEnum::ADMIN_CHURCH->value,
            ProfileUniqueNameEnum::ADMIN_MODULE->value,
            ProfileUniqueNameEnum::ASSISTANT->value,
            ProfileUniqueNameEnum::MEMBER->value,
        ];

        if(!in_array($profile->unique_name, $allowedProfiles))
        {
            throw new AppException(
                MessagesEnum::INVALID_PROFILE,
                Response::HTTP_BAD_REQUEST
            );
        }

        return $profile;
    }

    /**
     * @throws AppException
     */
    public static function memberExists(
        string $userId,
        MembersFiltersDTO $membersFiltersDTO,
        MembersRepositoryInterface $membersRepository
    ): object
    {
        if(!$userMember = $membersRepository->findOneByFilters($userId, $membersFiltersDTO))
        {
            throw new AppException(
                MessagesEnum::USER_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        if(empty($userMember->person_id))
        {
            throw new AppException(
                MessagesEnum::USER_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        return $userMember;
    }

    /**
     * @throws AppException
     */
    public static function emailAlreadyExistsInUpdate(
        string $userId,
        string $email,
        UsersRepositoryInterface $usersRepository,
    ): void
    {
        $user = $usersRepository->findByEmail($email);

        if($user && $user->id != $userId)
        {
            UsersValidations::emailAlreadyExistsUpdateException();
        }
    }

    /**
     * @throws AppException
     */
    public static function phoneAlreadyExistsInUpdate(
        string $userId,
        string $phone,
        UsersRepositoryInterface $usersRepository,
    ): void
    {
        $user = $usersRepository->findByPhone($phone);

        if($user && $user->id != $userId)
        {
            UsersValidations::phoneAlreadyExistsUpdateException();
        }
    }

    /**
     * @throws AppException
     */
    public static function memberUserHasChurch(mixed $member, Collection $churchesUserMember): void
    {
        $churchesCollect = collect($member->church);

        $userLoggedChurchesId = $churchesUserMember->pluck('id')->toArray();

        if(empty($churchesCollect->whereIn('id', $userLoggedChurchesId)->first()))
        {
            throw new AppException(
                MessagesEnum::ACCESS_DENIED,
                Response::HTTP_FORBIDDEN
            );
        }
    }
}
