<?php

namespace App\Modules\Store\Departments\Services;

use App\Base\Services\AuthenticatedService;
use App\Base\Traits\EnvironmentException;
use App\Exceptions\AppException;
use App\Modules\Store\Departments\Contracts\DepartmentsRepositoryInterface;
use App\Modules\Store\Departments\Contracts\UpdateDepartmentServiceInterface;
use App\Modules\Store\Departments\DTO\DepartmentsDTO;
use App\Modules\Store\Departments\Validations\DepartmentsValidations;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class UpdateDepartmentService extends AuthenticatedService implements UpdateDepartmentServiceInterface
{
    public function __construct(
        private readonly DepartmentsRepositoryInterface $departmentsRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(DepartmentsDTO $departmentsDTO): object
    {
        $this->getPolicy()->havePermission(RulesEnum::STORE_MODULE_DEPARTMENTS_UPDATE->value);

        DepartmentsValidations::departmentExists(
            $departmentsDTO->id,
            $this->departmentsRepository,
        );

        DepartmentsValidations::departmentExistsByNameInUpdate(
            $departmentsDTO->id,
            $departmentsDTO->name,
            $this->departmentsRepository
        );
        Transaction::beginTransaction();

        try
        {
            $updated = $this->departmentsRepository->save($departmentsDTO);

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
