<?php

namespace App\Modules\Store\Categories\Services;

use App\Base\Services\AuthenticatedService;
use App\Exceptions\AppException;
use App\Modules\Store\Categories\Contracts\FindByCategoryIdServiceInterface;
use App\Modules\Store\Categories\Contracts\CategoriesRepositoryInterface;
use App\Modules\Store\Categories\Validations\CategoriesValidators;
use App\Shared\Enums\RulesEnum;

class FindByCategoryIdService extends AuthenticatedService implements FindByCategoryIdServiceInterface
{
    public function __construct(
        private readonly CategoriesRepositoryInterface $categoriesRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(string $id): object
    {
        $this->getPolicy()->havePermission(RulesEnum::STORE_MODULE_CATEGORIES_VIEW->value);

        return CategoriesValidators::categoryExists(
            $id,
            $this->categoriesRepository,
            true
        );
    }
}
