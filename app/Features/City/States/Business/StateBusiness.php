<?php

namespace App\Features\City\States\Business;

use App\Shared\Enums\CacheEnum;
use App\Shared\Enums\MessagesEnum;
use App\Exceptions\AppException;
use App\Features\City\States\Contracts\StateBusinessInterface;
use App\Features\City\States\Contracts\StateRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

readonly class StateBusiness implements StateBusinessInterface
{
    public function __construct(
        private StateRepositoryInterface $stateRepository
    ) {}

    public function findAll()
    {
        return Cache::rememberForever(
            CacheEnum::STATES->value,
            function() {
                return $this->stateRepository->findAll();
            }
        );
    }

    /**
     * @throws AppException
     */
    public function findById(string $id)
    {
        $city = $this->stateRepository->findById($id);

        if(empty($city)) {
            throw new AppException(
                MessagesEnum::REGISTER_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        return $city;
    }

    /**
     * @throws AppException
     */
    public function findByUF(string $uf)
    {
        $uf = strtoupper($uf);

        $city = $this->stateRepository->findByUF($uf);

        if(empty($city)) {
            throw new AppException(
                MessagesEnum::REGISTER_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        return $city;
    }
}
