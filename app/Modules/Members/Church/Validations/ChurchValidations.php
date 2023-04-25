<?php

namespace App\Modules\Members\Church\Validations;

use App\Exceptions\AppException;
use App\Features\Users\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\Users\AdminUsers\DTO\AdminUsersFiltersDTO;
use App\Modules\Members\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Members\ResponsibleChurch\Contracts\ResponsibleChurchRepositoryInterface;
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
        AdminUsersFiltersDTO $adminUsersFiltersDTO
    ): void
    {
        $notFound = [];

        $users = $adminUsersRepository->findAll($adminUsersFiltersDTO);

        $ids = collect($users)->pluck('admin_user_id')->toArray();

        foreach ($adminUsersFiltersDTO->adminsId as $adminId)
        {
            if(!in_array($adminId, $ids))
            {
                $notFound[] = $adminId;
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

    /**
     * @throws AppException
     */
    public static function responsibleRelationshipExists(
        string $adminUserId,
        string $churchId,
        ResponsibleChurchRepositoryInterface $responsibleChurchRepository
    ): void
    {
        if(!$responsibleChurchRepository->findByAdminUserAndChurch($adminUserId, $churchId))
        {
            throw new AppException(
                MessagesEnum::REGISTER_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }
    }
}
