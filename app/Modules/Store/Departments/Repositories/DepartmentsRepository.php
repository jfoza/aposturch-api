<?php

namespace App\Modules\Store\Departments\Repositories;

use App\Base\Http\Pagination\PaginationOrder;
use App\Base\Traits\BuilderTrait;
use App\Modules\Store\Departments\Contracts\DepartmentsRepositoryInterface;
use App\Modules\Store\Departments\DTO\DepartmentsDTO;
use App\Modules\Store\Departments\DTO\DepartmentsFiltersDTO;
use App\Modules\Store\Departments\Models\Department;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class DepartmentsRepository implements DepartmentsRepositoryInterface
{
    use BuilderTrait;

    public function findAll(DepartmentsFiltersDTO $departmentsFiltersDTO): LengthAwarePaginator|Collection
    {
        $builder = $this
            ->getBaseQuery()
            ->when(
                isset($departmentsFiltersDTO->name),
                fn($q) => $q->where(
                    Department::NAME,
                    'ilike',
                    "%$departmentsFiltersDTO->name%"
                )
            )
            ->when(
                isset($departmentsFiltersDTO->hasSubcategories),
                function ($q) use($departmentsFiltersDTO) {
                    if($departmentsFiltersDTO->hasSubcategories === true)
                    {
                        return $q->withCount('subcategory')->has('subcategory', '>', 0);
                    }

                    return $q->withCount('subcategory')->has('subcategory', '=', 0);
                }
            )
            ->when(
                isset($departmentsFiltersDTO->active),
                fn($q) => $q->where(Department::tableField(Department::ACTIVE), $departmentsFiltersDTO->active)
            )
            ->orderBy(
                $this->getColumnName($departmentsFiltersDTO->paginationOrder),
                $departmentsFiltersDTO->paginationOrder->getColumnOrder(),
            );

        return $this->paginateOrGet(
            $builder,
            $departmentsFiltersDTO->paginationOrder
        );
    }

    public function findAllByIds(array $departmentsId): Collection
    {
        $departments = $this->getBaseQuery()->whereIn(Department::ID, $departmentsId)->get();

        return collect($departments);
    }

    public function findById(string $id): ?object
    {
        return $this->getBaseQuery()->find($id);
    }

    public function findByName(string $name): ?object
    {
        return $this->getBaseQuery()->where(Department::NAME, $name)->first();
    }

    public function create(DepartmentsDTO $departmentsDTO): object
    {
        return Department::create([
            Department::NAME        => $departmentsDTO->name,
            Department::DESCRIPTION => $departmentsDTO->description,
        ]);
    }

    public function save(DepartmentsDTO $departmentsDTO): object
    {
        $update = [
            Department::ID          => $departmentsDTO->id,
            Department::NAME        => $departmentsDTO->name,
            Department::DESCRIPTION => $departmentsDTO->description,
        ];

        Department::where(Department::ID, $departmentsDTO->id)->update($update);

        return (object) ($update);
    }

    public function updateStatus(string $id, bool $status): object
    {
        Department::find($id)->update([Department::ACTIVE => $status]);

        return (object) ([
            Department::ID     => $id,
            Department::ACTIVE => $status,
        ]);
    }

    public function remove(string $id): void
    {
        Department::find($id)->delete();
    }

    private function getBaseQuery()
    {
        return Department::select(
            Department::ID,
            Department::NAME,
            Department::DESCRIPTION,
            Department::ACTIVE,
            Department::CREATED_AT,
        );
    }

    private function getColumnName(PaginationOrder $paginationOrder): string
    {
        return match ($paginationOrder->getColumnName())
        {
            Department::NAME   => Department::tableField(Department::NAME),
            Department::ACTIVE => Department::tableField(Department::ACTIVE),
            default            => Department::tableField(Department::CREATED_AT)
        };
    }
}
