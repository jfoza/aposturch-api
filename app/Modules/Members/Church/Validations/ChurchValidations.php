<?php

namespace App\Modules\Members\Church\Validations;

use App\Exceptions\AppException;
use App\Modules\Members\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Members\Church\Models\Church;
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
    ): ?Church
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
    public static function churchExistsAndHasMembers(
        ChurchRepositoryInterface $churchRepository,
        string $churchId
    ): mixed
    {
        if(!$church = $churchRepository->findById($churchId, true))
        {
            throw new AppException(
                MessagesEnum::REGISTER_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        if(count($church->adminUser) > 0)
        {
            throw new AppException(
                MessagesEnum::CHURCH_HAS_MEMBERS_IN_DELETE,
                Response::HTTP_BAD_REQUEST
            );
        }

        return $church;
    }
}
