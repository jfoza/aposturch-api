<?php

namespace App\Features\City\Cities\Validations;

use App\Exceptions\AppException;
use App\Features\City\Cities\Contracts\CityRepositoryInterface;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\StatesEnum;
use Symfony\Component\HttpFoundation\Response;

class CityValidations
{
    /**
     * @throws AppException
     */
    public static function cityIdExists(
        CityRepositoryInterface $cityRepository,
        string $cityId
    )
    {
        if(!$city = $cityRepository->findById($cityId))
        {
            throw new AppException(
                MessagesEnum::CITY_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        return $city;
    }

    /**
     * @throws AppException
     */
    public static function stateExists(string $uf): void
    {
        $states = array_column(StatesEnum::cases(), 'value');

        if(!in_array($uf, $states))
        {
            throw new AppException(
                MessagesEnum::INVALID_UF,
                Response::HTTP_NOT_FOUND
            );
        }
    }
}
