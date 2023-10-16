<?php

namespace App\Modules\Store\Categories\Services;

use App\Base\Services\AuthenticatedService;
use App\Base\Traits\EnvironmentException;
use App\Exceptions\AppException;
use App\Modules\Store\Categories\Contracts\CategoriesRepositoryInterface;
use App\Modules\Store\Categories\Contracts\UpdateStatusCategoryServiceInterface;
use App\Modules\Store\Categories\Validations\CategoriesValidations;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;
use Illuminate\Support\Collection;

class UpdateStatusCategoryService extends AuthenticatedService implements UpdateStatusCategoryServiceInterface
{
    public function __construct(
        private readonly CategoriesRepositoryInterface $categoriesRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(array $categoriesId): Collection
    {
        $this->getPolicy()->havePermission(RulesEnum::STORE_MODULE_CATEGORIES_STATUS_UPDATE->value);

        $categories = CategoriesValidations::categoriesExists(
            $categoriesId,
            $this->categoriesRepository
        );

        Transaction::beginTransaction();

        try
        {
            $categories = $categories->map(
                fn($item) => $this->categoriesRepository->updateStatus($item->id, !$item->active)
            );

            Transaction::commit();

            return $categories;
        }
        catch (\Exception $e)
        {
            Transaction::rollback();

            EnvironmentException::dispatchException($e);
        }
    }
}
