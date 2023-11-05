<?php

namespace App\Modules\Store\Products\Services;

use App\Base\Services\AuthenticatedService;
use App\Modules\Store\Products\Contracts\ProductsRepositoryInterface;
use App\Modules\Store\Products\Contracts\ProductUniqueCodeGeneratorServiceInterface;
use App\Shared\Helpers\RandomStringHelper;

class ProductUniqueCodeGeneratorService extends AuthenticatedService implements ProductUniqueCodeGeneratorServiceInterface
{
    public function __construct(
        private readonly ProductsRepositoryInterface $productsRepository
    ) {}

    public function execute(): array
    {
        do
        {
            $prefix = RandomStringHelper::alphaGenerate(2);
            $number = RandomStringHelper::numericGenerate(6);

            $code = strtoupper($prefix.$number);
        }
        while
        (
            !empty($this->productsRepository->findByCode($code))
        );

        return ['code' => $code];
    }
}
