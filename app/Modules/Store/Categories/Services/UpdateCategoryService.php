<?php

namespace App\Modules\Store\Categories\Services;

use App\Base\Exceptions\EnvironmentException;
use App\Base\Services\AuthenticatedService;
use App\Exceptions\AppException;
use App\Modules\Store\Categories\Contracts\CategoriesRepositoryInterface;
use App\Modules\Store\Categories\Contracts\UpdateCategoryServiceInterface;
use App\Modules\Store\Categories\DTO\CategoriesDTO;
use App\Modules\Store\Categories\Validations\CategoriesValidators;
use App\Modules\Store\Departments\Contracts\DepartmentsRepositoryInterface;
use App\Modules\Store\Departments\Validations\DepartmentsValidations;
use App\Modules\Store\Products\Contracts\ProductsRepositoryInterface;
use App\Modules\Store\Products\Validations\ProductsValidators;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class UpdateCategoryService extends AuthenticatedService implements UpdateCategoryServiceInterface
{
    private CategoriesDTO $categoriesDTO;

    public function __construct(
        private readonly DepartmentsRepositoryInterface $departmentsRepository,
        private readonly CategoriesRepositoryInterface  $categoriesRepository,
        private readonly ProductsRepositoryInterface    $productsRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(CategoriesDTO $categoriesDTO): object
    {
        $this->getPolicy()->havePermission(RulesEnum::STORE_MODULE_CATEGORIES_UPDATE->value);

        $this->categoriesDTO = $categoriesDTO;

        $this->handleValidations();

        Transaction::beginTransaction();

        try
        {
            $updated = $this->categoriesRepository->save($this->categoriesDTO);

            $this->categoriesRepository->saveProducts($updated->id, $this->categoriesDTO->productsId);

            Transaction::commit();

            return $updated;
        }
        catch (\Exception $e)
        {
            Transaction::rollback();

            EnvironmentException::dispatchException($e);
        }
    }

    /**
     * @throws AppException
     */
    private function handleValidations(): void
    {
        CategoriesValidators::categoryExists(
            $this->categoriesDTO->id,
            $this->categoriesRepository
        );

        CategoriesValidators::categoryExistsByNameInUpdate(
            $this->categoriesDTO->id,
            $this->categoriesDTO->name,
            $this->categoriesRepository
        );

        DepartmentsValidations::departmentExists(
            $this->categoriesDTO->departmentId,
            $this->departmentsRepository
        );

        if(count($this->categoriesDTO->productsId) > 0)
        {
            ProductsValidators::productsExists(
                $this->categoriesDTO->productsId,
                $this->productsRepository
            );
        }
    }
}
