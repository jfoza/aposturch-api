<?php

namespace App\Modules\Membership\Church\Validations;

use App\Exceptions\AppException;
use App\Modules\Membership\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Membership\Church\Models\Church;
use App\Shared\Enums\MessagesEnum;
use Illuminate\Support\Collection;
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
    public static function churchIdsExists(
        ChurchRepositoryInterface $churchRepository,
        array $churchIds
    ): void
    {
        if(!$churches = $churchRepository->findByIds($churchIds))
        {
            throw new AppException(
                MessagesEnum::REGISTER_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        $ids = $churches->pluck(Church::ID)->toArray();

        $notFound = [];

        foreach ($churchIds as $churchId)
        {
            if(!in_array($churchId, $ids))
            {
                $notFound[] = $churchId;
            }
        }

        if(!empty($notFound))
        {
            throw new AppException(
                MessagesEnum::REGISTER_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }
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
    public static function memberHasChurchById(
        string $churchId,
        Collection $churchesUserLogged
    ): void
    {
        if(!$churchesUserLogged->where(Church::ID, $churchId)->first())
        {
            throw new AppException(
                MessagesEnum::NOT_AUTHORIZED,
                Response::HTTP_FORBIDDEN
            );
        }
    }

    /**
     * @throws AppException
     */
    public static function memberHasChurchByUniqueName(
        string $churchUniqueName,
        Collection $churchesUserLogged
    ): void
    {
        if(!$churchesUserLogged->where(Church::UNIQUE_NAME, $churchUniqueName)->first())
        {
            throw new AppException(
                MessagesEnum::NOT_AUTHORIZED,
                Response::HTTP_FORBIDDEN
            );
        }
    }
}
