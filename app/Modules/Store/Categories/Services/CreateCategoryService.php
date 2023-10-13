<?php

namespace App\Modules\Store\Categories\Services;

use App\Base\Services\AuthenticatedService;
use App\Base\Traits\EnvironmentException;
use App\Exceptions\AppException;
use App\Modules\Store\Categories\Contracts\CategoriesRepositoryInterface;
use App\Modules\Store\Categories\Contracts\CreateCategoryServiceInterface;
use App\Modules\Store\Categories\DTO\CategoriesDTO;
use App\Modules\Store\Categories\Validations\CategoriesValidations;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class CreateCategoryService extends AuthenticatedService implements CreateCategoryServiceInterface
{
    public function __construct(
        private readonly CategoriesRepositoryInterface $categoriesRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(CategoriesDTO $categoriesDTO): object
    {
        $this->getPolicy()->havePermission(RulesEnum::STORE_MODULE_CATEGORIES_INSERT->value);

        CategoriesValidations::categoryExistsByName(
            $categoriesDTO->name,
            $this->categoriesRepository
        );

        Transaction::beginTransaction();

        try
        {
            $created = $this->categoriesRepository->create($categoriesDTO);

            Transaction::commit();

            return $created;
        }
        catch (\Exception $e)
        {
            Transaction::rollback();

            EnvironmentException::dispatchException($e);
        }
    }
}
