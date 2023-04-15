<?php

namespace App\Features\City\Cities\Business;

use App\Features\City\Cities\Validations\CityValidations;
use App\Shared\Enums\CacheEnum;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\StatesEnum;
use App\Exceptions\AppException;
use App\Features\City\Cities\Contracts\CityBusinessInterface;
use App\Features\City\Cities\Contracts\CityRepositoryInterface;
use App\Features\City\States\Contracts\StateRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

readonly class CityBusiness implements CityBusinessInterface
{
    public function __construct(
        private CityRepositoryInterface $cityRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function findByUF(string $uf)
    {
        $uf = strtoupper($uf);

        CityValidations::stateExists($uf);

        return $this->cityRepository->findByUF($uf);
    }

    /**
     * @throws AppException
     */
    public function findById(string $id)
    {
        return CityValidations::cityIdExists(
            $this->cityRepository,
            $id
        );
    }

    public function findAllInPersons()
    {
        return $this->cityRepository->findAllInPersons();
    }
}
