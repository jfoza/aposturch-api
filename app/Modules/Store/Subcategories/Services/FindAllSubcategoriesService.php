<?php

namespace App\Modules\Store\Subcategories\Services;

use App\Base\Services\AuthenticatedService;
use App\Exceptions\AppException;
use App\Modules\Store\Subcategories\Contracts\FindAllSubcategoriesServiceInterface;
use App\Modules\Store\Subcategories\Contracts\SubcategoriesRepositoryInterface;
use App\Modules\Store\Subcategories\DTO\SubcategoriesFiltersDTO;
use App\Shared\Enums\RulesEnum;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class FindAllSubcategoriesService extends AuthenticatedService implements FindAllSubcategoriesServiceInterface
{
    public function __construct(
        private readonly SubcategoriesRepositoryInterface $subcategoriesRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(SubcategoriesFiltersDTO $subcategoriesFiltersDTO): LengthAwarePaginator|Collection
    {
        $this->getPolicy()->havePermission(RulesEnum::STORE_MODULE_SUBCATEGORIES_VIEW->value);

        return $this->subcategoriesRepository->findAll($subcategoriesFiltersDTO);
    }
}
