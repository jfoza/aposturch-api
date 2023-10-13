<?php

namespace App\Modules\Store\Categories\Services;

use App\Base\Services\AuthenticatedService;
use App\Base\Traits\EnvironmentException;
use App\Exceptions\AppException;
use App\Modules\Store\Categories\Contracts\CategoriesRepositoryInterface;
use App\Modules\Store\Categories\Contracts\UpdateCategoryServiceInterface;
use App\Modules\Store\Categories\DTO\CategoriesDTO;
use App\Modules\Store\Categories\Validations\CategoriesValidations;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class UpdateCategoryService extends AuthenticatedService implements UpdateCategoryServiceInterface
{
    public function __construct(
        private readonly CategoriesRepositoryInterface $categoriesRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(CategoriesDTO $categoriesDTO): object
    {
        $this->getPolicy()->havePermission(RulesEnum::STORE_MODULE_CATEGORIES_UPDATE->value);

        CategoriesValidations::categoryExists(
            $categoriesDTO->id,
            $this->categoriesRepository,
        );

        CategoriesValidations::categoryExistsByNameInUpdate(
            $categoriesDTO->id,
            $categoriesDTO->name,
            $this->categoriesRepository
        );

        Transaction::beginTransaction();

        try
        {
            $updated = $this->categoriesRepository->save($categoriesDTO);

            Transaction::commit();

            return $updated;
        }
        catch (\Exception $e)
        {
            Transaction::rollback();

            EnvironmentException::dispatchException($e);
        }
    }
}
