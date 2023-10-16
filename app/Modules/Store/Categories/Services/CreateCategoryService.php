<?php

namespace App\Modules\Store\Categories\Services;

use App\Base\Services\AuthenticatedService;
use App\Base\Traits\EnvironmentException;
use App\Exceptions\AppException;
use App\Modules\Store\Categories\Contracts\CategoriesRepositoryInterface;
use App\Modules\Store\Categories\Contracts\CreateCategoryServiceInterface;
use App\Modules\Store\Categories\DTO\CategoriesDTO;
use App\Modules\Store\Categories\Validations\CategoriesValidations;
use App\Modules\Store\Subcategories\Contracts\SubcategoriesRepositoryInterface;
use App\Modules\Store\Subcategories\Validations\SubcategoriesValidations;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class CreateCategoryService extends AuthenticatedService implements CreateCategoryServiceInterface
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
        $this->getPolicy()->havePermission(RulesEnum::STORE_MODULE_CATEGORIES_INSERT->value);

        $this->categoriesDTO = $categoriesDTO;

        $this->handleValidations();

        Transaction::beginTransaction();

        try
        {
            $created = $this->categoriesRepository->create($this->categoriesDTO);

            if($this->hasSubcategories)
            {
                $this
                    ->subcategoriesRepository
                    ->saveCategory(
                        $created->id,
                        $this->categoriesDTO->subcategoriesId
                    );
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
        $this->hasSubcategories = isset($this->categoriesDTO->subcategoriesId) && count($this->categoriesDTO->subcategoriesId) > 0;

        CategoriesValidations::categoryExistsByName(
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
