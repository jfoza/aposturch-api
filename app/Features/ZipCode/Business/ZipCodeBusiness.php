<?php

namespace App\Features\ZipCode\Business;

use App\Exceptions\AppException;
use App\Shared\Helpers\Helpers;
use App\Features\City\Cities\Contracts\CityRepositoryInterface;
use App\Features\ZipCode\Contracts\ZipCodeBusinessInterface;
use App\Features\ZipCode\Http\Responses\ZipCodeResponse;
use App\Features\ZipCode\Services\AddressByZipCodeService;

readonly class ZipCodeBusiness implements ZipCodeBusinessInterface
{
    public function __construct(
        private CityRepositoryInterface $cityRepository,
        private AddressByZipCodeService $addressByZipCodeService,
        private ZipCodeResponse         $zipCodeResponse
    ) {}

    /**
     * @throws AppException
     */
    public function findByZipCode(string $zipCode): ZipCodeResponse
    {
        $address = $this->addressByZipCodeService->execute($zipCode);

        $city = $this->cityRepository->findByDescription(
            $address->localidade,
            $address->uf
        );

        $citiesByUF = $this->cityRepository->findByUF($address->uf)->toArray();

        $this->zipCodeResponse->zipCode    = Helpers::onlyNumbers($address->cep);
        $this->zipCodeResponse->address    = $address->logradouro;
        $this->zipCodeResponse->district   = $address->bairro;
        $this->zipCodeResponse->city       = $city;
        $this->zipCodeResponse->citiesByUF = $citiesByUF;

        return $this->zipCodeResponse;
    }
}
