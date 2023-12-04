<?php

namespace App\Modules\Store\Departments\Services;

use App\Base\Exceptions\EnvironmentException;
use App\Base\Services\AuthenticatedService;
use App\Exceptions\AppException;
use App\Modules\Store\Categories\Contracts\CategoriesRepositoryInterface;
use App\Modules\Store\Departments\Contracts\DepartmentsRepositoryInterface;
use App\Modules\Store\Departments\Contracts\RemoveDepartmentServiceInterface;
use App\Modules\Store\Departments\Validations\DepartmentsValidations;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class RemoveDepartmentService extends AuthenticatedService implements RemoveDepartmentServiceInterface
{
    public function __construct(
        private readonly DepartmentsRepositoryInterface $departmentsRepository,
        private readonly CategoriesRepositoryInterface  $categoriesRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(string $id): void
    {
        $this->getPolicy()->havePermission(RulesEnum::STORE_MODULE_DEPARTMENTS_DELETE->value);

        DepartmentsValidations::departmentExists(
            $id,
            $this->departmentsRepository,
        );

        DepartmentsValidations::hasCategories(
            $id,
            $this->categoriesRepository
        );

        Transaction::beginTransaction();

        try
        {
            $this->departmentsRepository->remove($id);

            Transaction::commit();
        }
        catch (\Exception $e)
        {
            Transaction::rollback();

            EnvironmentException::dispatchException($e);
        }
    }
}
