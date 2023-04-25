<?php

namespace App\Modules\Members\Church\Validations;

use App\Exceptions\AppException;
use App\Features\Users\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\Users\Users\Infra\Models\User;
use App\Modules\Members\Church\Contracts\ChurchRepositoryInterface;
use App\Shared\Enums\MessagesEnum;
use Symfony\Component\HttpFoundation\Response;

class ChurchValidations
{
    /**
     * @throws AppException
     */
    public static function churchIdExists(
        ChurchRepositoryInterface $churchRepository,
        string $churchId
    ): object|null
    {
        if(!$church = $churchRepository->findById($churchId))
        {
            throw new AppException(
                MessagesEnum::REGISTER_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        return $church;
    }

    /**
     * @throws AppException
     */
    public static function churchUniqueNameExists(
        ChurchRepositoryInterface $churchRepository,
        string $churchUniqueName
    ): object|null
    {
        if(!$church = $churchRepository->findByUniqueName($churchUniqueName))
        {
            throw new AppException(
                MessagesEnum::REGISTER_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        return $church;
    }

    /**
     * @throws AppException
     */
    public static function isValidAdminsChurch(
        AdminUsersRepositoryInterface  $adminUsersRepository,
        array $usersIdPayload
    ): void
    {
        $notFound = [];

        $users = $adminUsersRepository->findByAdminIdsAndProfile(
            $usersIdPayload,
            ProfileUniqueNameEnum::ADMIN_CHURCH
        );

        $ids = collect($users)->pluck(User::ID)->toArray();

        foreach ($usersIdPayload as $userIdPayload)
        {
            if(!in_array($userIdPayload, $ids))
            {
                $notFound[] = $userIdPayload;
            }
        }

        if(!empty($notFound))
        {
            throw new AppException(
                MessagesEnum::USER_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }
    }
}
