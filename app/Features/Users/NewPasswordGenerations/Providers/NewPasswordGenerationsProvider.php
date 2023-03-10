<?php

namespace App\Features\Users\NewPasswordGenerations\Providers;

use App\Features\Base\Providers\ServiceProviderAbstract;
use App\Features\Users\NewPasswordGenerations\Business\NewPasswordGenerationsBusiness;
use App\Features\Users\NewPasswordGenerations\Contracts\NewPasswordGenerationsBusinessInterface;
use App\Features\Users\NewPasswordGenerations\Contracts\NewPasswordGenerationsRepositoryInterface;
use App\Features\Users\NewPasswordGenerations\Infra\Repositories\NewPasswordGenerationsRepository;

class NewPasswordGenerationsProvider extends ServiceProviderAbstract
{
    public array $bindings = [
        NewPasswordGenerationsRepositoryInterface::class => NewPasswordGenerationsRepository::class,
    ];

    public function getBusinessAbstract(): string
    {
        return NewPasswordGenerationsBusinessInterface::class;
    }

    public function getBusinessConcrete(): string
    {
        return NewPasswordGenerationsBusiness::class;
    }
}
