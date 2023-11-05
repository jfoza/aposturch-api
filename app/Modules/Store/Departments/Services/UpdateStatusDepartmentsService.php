<?php

namespace App\Modules\Store\Departments\Services;

use App\Base\Services\AuthenticatedService;
use App\Base\Traits\EnvironmentException;
use App\Exceptions\AppException;
use App\Modules\Store\Departments\Contracts\DepartmentsRepositoryInterface;
use App\Modules\Store\Departments\Contracts\UpdateStatusDepartmentsServiceInterface;
use App\Modules\Store\Departments\Validations\DepartmentsValidations;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;
use Illuminate\Support\Collection;

class UpdateStatusDepartmentsService extends AuthenticatedService implements UpdateStatusDepartmentsServiceInterface
{
    public function __construct(
        private readonly DepartmentsRepositoryInterface $departmentsRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(array $departmentsId): Collection
    {
        $this->getPolicy()->havePermission(RulesEnum::STORE_MODULE_DEPARTMENTS_STATUS_UPDATE->value);

        $departments = DepartmentsValidations::departmentsExists(
            $departmentsId,
            $this->departmentsRepository
        );

        Transaction::beginTransaction();

        try
        {
            $departments = $departments->map(
                fn($item) => $this->departmentsRepository->updateStatus($item->id, !$item->active)
            );

            Transaction::commit();

            return $departments;
        }
        catch (\Exception $e)
        {
            Transaction::rollback();

            EnvironmentException::dispatchException($e);
        }
    }
}
