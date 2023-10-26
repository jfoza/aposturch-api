<?php

namespace App\Modules\Store\Subcategories\Services;

use App\Base\Services\AuthenticatedService;
use App\Exceptions\AppException;
use App\Modules\Store\Subcategories\Contracts\FindBySubcategoryIdServiceInterface;
use App\Modules\Store\Subcategories\Contracts\SubcategoriesRepositoryInterface;
use App\Modules\Store\Subcategories\Validations\SubcategoriesValidators;
use App\Shared\Enums\RulesEnum;

class FindBySubcategoryIdService extends AuthenticatedService implements FindBySubcategoryIdServiceInterface
{
    public function __construct(
        private readonly SubcategoriesRepositoryInterface $subcategoriesRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(string $id): object
    {
        $this->getPolicy()->havePermission(RulesEnum::STORE_MODULE_SUBCATEGORIES_VIEW->value);

        return SubcategoriesValidators::subcategoryExists(
            $id,
            $this->subcategoriesRepository,
            true
        );
    }
}
