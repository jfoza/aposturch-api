<?php

namespace App\Modules\Store\Departments\Services;

use App\Base\Exceptions\EnvironmentException;
use App\Base\Services\AuthenticatedService;
use App\Exceptions\AppException;
use App\Modules\Store\Departments\Contracts\CreateDepartmentServiceInterface;
use App\Modules\Store\Departments\Contracts\DepartmentsRepositoryInterface;
use App\Modules\Store\Departments\DTO\DepartmentsDTO;
use App\Modules\Store\Departments\Validations\DepartmentsValidations;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class CreateDepartmentService extends AuthenticatedService implements CreateDepartmentServiceInterface
{
    public function __construct(
        private readonly DepartmentsRepositoryInterface $departmentsRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(DepartmentsDTO $departmentsDTO): object
    {
        $this->getPolicy()->havePermission(RulesEnum::STORE_MODULE_DEPARTMENTS_INSERT->value);

        DepartmentsValidations::departmentExistsByName(
            $departmentsDTO->name,
            $this->departmentsRepository
        );

        Transaction::beginTransaction();

        try
        {
            $created = $this->departmentsRepository->create($departmentsDTO);

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
