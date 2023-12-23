<?php

namespace App\Modules\Store\Products\Services;

use App\Base\Services\AuthenticatedService;
use App\Exceptions\AppException;
use App\Modules\Store\Products\Contracts\ProductsRepositoryInterface;
use App\Modules\Store\Products\Contracts\ShowByProductUniqueNameServiceInterface;
use App\Modules\Store\Products\Validations\ProductsValidators;
use App\Shared\Enums\RulesEnum;

class ShowByProductUniqueNameService extends AuthenticatedService implements ShowByProductUniqueNameServiceInterface
{
    public function __construct(
        private readonly ProductsRepositoryInterface $productsRepository
    ) {}

    /**
     * @throws AppException
     */
    public function execute(string $uniqueName): object
    {
        $this->getPolicy()->havePermission(RulesEnum::STORE_MODULE_PRODUCTS_VIEW->value);

        return ProductsValidators::productExistsByUniqueName(
            $uniqueName,
            $this->productsRepository,
        );
    }
}
