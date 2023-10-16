<?php

namespace App\Modules\Store\Categories\Services;

use App\Base\Services\AuthenticatedService;
use App\Base\Traits\EnvironmentException;
use App\Exceptions\AppException;
use App\Modules\Store\Categories\Contracts\CategoriesRepositoryInterface;
use App\Modules\Store\Categories\Contracts\UpdateCategoryServiceInterface;
use App\Modules\Store\Categories\DTO\CategoriesDTO;
use App\Modules\Store\Categories\Validations\CategoriesValidations;
use App\Modules\Store\Subcategories\Contracts\SubcategoriesRepositoryInterface;
use App\Modules\Store\Subcategories\Validations\SubcategoriesValidations;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class UpdateCategoryService extends AuthenticatedService implements UpdateCategoryServiceInterface
{
    private CategoriesDTO $categoriesDTO;
    private bool $hasSubcategories = false;

    public function __construct(
        private readonly CategoriesRepositoryInterface $categoriesRepository,
        private readonly SubcategoriesRepositoryInterface $subcategoriesRepository,
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

            if($this->hasSubcategories)
            {
                $this->subcategoriesRepository->saveCategory($updated->id, $this->categoriesDTO->subcategoriesId);
            }

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
        $this->hasSubcategories = isset($this->categoriesDTO->subcategoriesId) && count($this->categoriesDTO->subcategoriesId) > 0;

        CategoriesValidations::categoryExists(
            $this->categoriesDTO->id,
            $this->categoriesRepository,
        );

        CategoriesValidations::categoryExistsByNameInUpdate(
            $this->categoriesDTO->id,
            $this->categoriesDTO->name,
            $this->categoriesRepository
        );

        if($this->hasSubcategories)
        {
            SubcategoriesValidations::subcategoriesExists(
                $this->categoriesDTO->subcategoriesId,
                $this->subcategoriesRepository,
            );
        }
    }
}
