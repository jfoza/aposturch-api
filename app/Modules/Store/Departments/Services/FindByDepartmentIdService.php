<?php

namespace App\Modules\Store\Departments\Services;

use App\Base\Services\AuthenticatedService;
use App\Exceptions\AppException;
use App\Modules\Store\Departments\Contracts\DepartmentsRepositoryInterface;
use App\Modules\Store\Departments\Contracts\FindByDepartmentIdServiceInterface;
use App\Modules\Store\Departments\Validations\DepartmentsValidations;
use App\Shared\Enums\RulesEnum;

class FindByDepartmentIdService extends AuthenticatedService implements FindByDepartmentIdServiceInterface
{
    public function __construct(
        private readonly DepartmentsRepositoryInterface $departmentsRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(string $id): object
    {
        $this->getPolicy()->havePermission(RulesEnum::STORE_MODULE_DEPARTMENTS_VIEW->value);

        return DepartmentsValidations::departmentExists(
            $id,
            $this->departmentsRepository,
        );
    }
}
