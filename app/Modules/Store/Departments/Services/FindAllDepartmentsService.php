<?php

namespace App\Modules\Store\Departments\Services;

use App\Base\Services\AuthenticatedService;
use App\Exceptions\AppException;
use App\Modules\Store\Departments\Contracts\DepartmentsRepositoryInterface;
use App\Modules\Store\Departments\Contracts\FindAllDepartmentsServiceInterface;
use App\Modules\Store\Departments\DTO\DepartmentsFiltersDTO;
use App\Shared\Enums\RulesEnum;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class FindAllDepartmentsService extends AuthenticatedService implements FindAllDepartmentsServiceInterface
{
    public function __construct(
        private readonly DepartmentsRepositoryInterface $departmentsRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(DepartmentsFiltersDTO $departmentsFiltersDTO): LengthAwarePaginator|Collection
    {
        $this->getPolicy()->havePermission(RulesEnum::STORE_MODULE_DEPARTMENTS_VIEW->value);

        return $this->departmentsRepository->findAll($departmentsFiltersDTO);
    }
}
