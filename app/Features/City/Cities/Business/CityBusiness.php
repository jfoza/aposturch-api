<?php

namespace App\Features\City\Cities\Business;

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

        $states = array_column(StatesEnum::cases(), 'value');

        if(!in_array($uf, $states))
        {
            throw new AppException(
                MessagesEnum::INVALID_UF,
                Response::HTTP_NOT_FOUND
            );
        }

        return Cache::rememberForever(
            'CITIES_BY_'.$uf,
            function() use($uf) {
                return $this->cityRepository->findByUF($uf);
            }
        );
    }

    /**
     * @throws AppException
     */
    public function findById(string $id)
    {
        $city = $this->cityRepository->findById($id);

        if(empty($city)) {
            throw new AppException(
                [
                    'field'   => 'cities',
                    'message' => MessagesEnum::REGISTER_NOT_FOUND,
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        return $city;
    }

    public function findAllInPersons()
    {
        return $this->cityRepository->findAllInPersons();
    }
}
