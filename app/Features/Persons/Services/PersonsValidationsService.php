<?php

namespace App\Features\Persons\Services;

use App\Shared\Enums\MessagesEnum;
use App\Exceptions\AppException;
use App\Features\City\Cities\Contracts\CityRepositoryInterface;
use App\Features\City\States\Contracts\StateRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

class PersonsValidationsService
{
    /**
     * @throws AppException
     */
    public static function validateCityId(
        CityRepositoryInterface $cityRepository,
        string $cityId
    ): void
    {
        if(empty($cityRepository->findById($cityId))) {
            throw new AppException(
                MessagesEnum::CITY_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }
    }

    /**
     * @throws AppException
     */
    public static function validateUF(
        StateRepositoryInterface $stateRepository,
        string $uf
    ): void
    {
        if(empty($stateRepository->findByUF($uf))) {
            throw new AppException(
                MessagesEnum::INVALID_UF,
                Response::HTTP_NOT_FOUND
            );
        }
    }
}
