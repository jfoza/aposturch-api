<?php

namespace App\Modules\Store\Subcategories\Services;

use App\Base\Services\AuthenticatedService;
use App\Base\Traits\EnvironmentException;
use App\Exceptions\AppException;
use App\Modules\Store\Subcategories\Contracts\SubcategoriesRepositoryInterface;
use App\Modules\Store\Subcategories\Contracts\UpdateStatusSubcategoriesServiceInterface;
use App\Modules\Store\Subcategories\Validations\SubcategoriesValidators;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;
use Illuminate\Support\Collection;

class UpdateStatusSubcategoriesService extends AuthenticatedService implements UpdateStatusSubcategoriesServiceInterface
{
    public function __construct(
        private readonly SubcategoriesRepositoryInterface $subcategoriesRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(array $subcategoriesId): Collection
    {
        $this->getPolicy()->havePermission(RulesEnum::STORE_MODULE_SUBCATEGORIES_STATUS_UPDATE->value);

        $subcategories = SubcategoriesValidators::subcategoriesExists(
            $subcategoriesId,
            $this->subcategoriesRepository
        );

        Transaction::beginTransaction();

        try
        {
            $subcategories = $subcategories->map(
                fn($item) => $this->subcategoriesRepository->updateStatus($item->id, !$item->active)
            );

            Transaction::commit();

            return $subcategories;
        }
        catch (\Exception $e)
        {
            Transaction::rollback();

            EnvironmentException::dispatchException($e);
        }
    }
}
