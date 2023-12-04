<?php

namespace App\Modules\Store\Categories\Services;

use App\Base\Exceptions\EnvironmentException;
use App\Base\Services\AuthenticatedService;
use App\Exceptions\AppException;
use App\Modules\Store\Categories\Contracts\CategoriesRepositoryInterface;
use App\Modules\Store\Categories\Contracts\CreateCategoryServiceInterface;
use App\Modules\Store\Categories\DTO\CategoriesDTO;
use App\Modules\Store\Categories\Validations\CategoriesValidators;
use App\Modules\Store\Departments\Contracts\DepartmentsRepositoryInterface;
use App\Modules\Store\Departments\Validations\DepartmentsValidations;
use App\Modules\Store\Products\Contracts\ProductsRepositoryInterface;
use App\Modules\Store\Products\Validations\ProductsValidators;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class CreateCategoryService extends AuthenticatedService implements CreateCategoryServiceInterface
{
    private CategoriesDTO $categoriesDTO;
    private bool $hasProducts = false;

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
        $this->getPolicy()->havePermission(RulesEnum::STORE_MODULE_CATEGORIES_INSERT->value);

        $this->categoriesDTO = $categoriesDTO;

        $this->handleValidations();

        Transaction::beginTransaction();

        try
        {
            $created = $this->categoriesRepository->create($this->categoriesDTO);

            if($this->hasProducts)
            {
                $this->categoriesRepository->saveProducts($created->id, $this->categoriesDTO->productsId);
            }

            Transaction::commit();

            return $created;
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
        $this->hasProducts = isset($this->categoriesDTO->productsId) && count($this->categoriesDTO->productsId) > 0;

        DepartmentsValidations::departmentExists(
            $this->categoriesDTO->departmentId,
            $this->departmentsRepository
        );

        CategoriesValidators::categoryExistsByName(
            $this->categoriesDTO->name,
            $this->categoriesRepository
        );

        if($this->hasProducts)
        {
            ProductsValidators::productsExists(
                $this->categoriesDTO->productsId,
                $this->productsRepository
            );
        }
    }
}
