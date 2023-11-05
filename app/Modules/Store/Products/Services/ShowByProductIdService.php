<?php

namespace App\Modules\Store\Products\Services;

use App\Base\Services\AuthenticatedService;
use App\Exceptions\AppException;
use App\Modules\Store\Products\Contracts\ProductsRepositoryInterface;
use App\Modules\Store\Products\Contracts\ShowByProductIdServiceInterface;
use App\Modules\Store\Products\Validations\ProductsValidators;
use App\Shared\Enums\RulesEnum;

class ShowByProductIdService extends AuthenticatedService implements ShowByProductIdServiceInterface
{
    public function __construct(
        private readonly ProductsRepositoryInterface $productsRepository
    ) {}

    /**
     * @throws AppException
     */
    public function execute(string $id): object
    {
        $this->getPolicy()->havePermission(RulesEnum::STORE_MODULE_PRODUCTS_VIEW->value);

        return ProductsValidators::productExists(
            $id,
            $this->productsRepository,
        );
    }
}
