<?php

namespace App\Modules\Store\Categories\Services;

use App\Base\Services\AuthenticatedService;
use App\Exceptions\AppException;
use App\Modules\Store\Categories\Contracts\FindAllCategoriesServiceInterface;
use App\Modules\Store\Categories\Contracts\CategoriesRepositoryInterface;
use App\Modules\Store\Categories\DTO\CategoriesFiltersDTO;
use App\Shared\Enums\RulesEnum;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class FindAllCategoriesService extends AuthenticatedService implements FindAllCategoriesServiceInterface
{
    public function __construct(
        private readonly CategoriesRepositoryInterface $categoriesRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(CategoriesFiltersDTO $categoriesFiltersDTO): LengthAwarePaginator|Collection
    {
        $this->getPolicy()->havePermission(RulesEnum::STORE_MODULE_CATEGORIES_VIEW->value);

        return $this->categoriesRepository->findAll($categoriesFiltersDTO);
    }
}
