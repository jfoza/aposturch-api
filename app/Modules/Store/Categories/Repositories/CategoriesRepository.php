<?php

namespace App\Modules\Store\Categories\Repositories;

use App\Base\Http\Pagination\PaginationOrder;
use App\Base\Traits\BuilderTrait;
use App\Modules\Store\Categories\Contracts\CategoriesRepositoryInterface;
use App\Modules\Store\Categories\DTO\CategoriesDTO;
use App\Modules\Store\Categories\DTO\CategoriesFiltersDTO;
use App\Modules\Store\Categories\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class CategoriesRepository implements CategoriesRepositoryInterface
{
    use BuilderTrait;

    public function findAll(CategoriesFiltersDTO $categoriesFiltersDTO): LengthAwarePaginator|Collection
    {
        $builder = $this
            ->getBaseQuery()
            ->when(
                isset($categoriesFiltersDTO->name),
                fn($q) => $q->where(
                    Category::tableField(Category::NAME),
                    'ilike',
                    "%$categoriesFiltersDTO->name%"
                )
            )
            ->when(
                isset($categoriesFiltersDTO->departmentId),
                fn($c) => $c->where(
                    Category::tableField(Category::DEPARTMENT_ID),
                    $categoriesFiltersDTO->departmentId
                )
            )
            ->when(
                isset($categoriesFiltersDTO->hasProducts),
                function ($q) use($categoriesFiltersDTO) {
                    if($categoriesFiltersDTO->hasProducts === true)
                    {
                        return $q->withCount('product')->has('product', '>', 0);
                    }

                    return $q->withCount('product')->has('product', '=', 0);
                }
            )
            ->when(
                isset($categoriesFiltersDTO->active),
                fn($q) => $q->where(Category::tableField(Category::ACTIVE), $categoriesFiltersDTO->active)
            )
            ->orderBy(
                $this->getColumnName($categoriesFiltersDTO->paginationOrder),
                $categoriesFiltersDTO->paginationOrder->getColumnOrder(),
            );

        return $this->paginateOrGet(
            $builder,
            $categoriesFiltersDTO->paginationOrder
        );
    }

    public function findAllByIds(array $categoriesId): Collection
    {
        $categories = $this->getBaseQuery()->whereIn(Category::ID, $categoriesId)->get();

        return collect($categories);
    }

    public function findById(string $id, bool $getProducts = false): ?object
    {
       return $this
           ->getBaseQuery()
           ->with($getProducts ? ['product'] : [])
           ->where(Category::ID, $id)
           ->first();
    }

    public function findByName(string $name): ?object
    {
        return $this
            ->getBaseQuery()
            ->where(Category::NAME, $name)
            ->first();
    }

    public function findByDepartment(string $departmentId): Collection
    {
        $categories = $this
            ->getBaseQuery()
            ->where(Category::DEPARTMENT_ID, $departmentId)
            ->get();

        return collect($categories);
    }

    public function create(CategoriesDTO $categoriesDTO): object
    {
        return Category::create([
            Category::DEPARTMENT_ID => $categoriesDTO->departmentId,
            Category::NAME          => $categoriesDTO->name,
            Category::DESCRIPTION   => $categoriesDTO->description,
        ]);
    }

    public function save(CategoriesDTO $categoriesDTO): object
    {
        $update = [
            Category::ID            => $categoriesDTO->id,
            Category::DEPARTMENT_ID => $categoriesDTO->departmentId,
            Category::NAME          => $categoriesDTO->name,
            Category::DESCRIPTION   => $categoriesDTO->description,
        ];

        Category::find($categoriesDTO->id)->update($update);

        return (object) ($update);
    }

    public function saveProducts(string $categoryId, array $productsId): void
    {
        Category::find($categoryId)->product()->sync($productsId);
    }

    public function remove(string $id): void
    {
        Category::where(Category::ID, $id)->delete();
    }

    public function updateStatus(string $id, bool $status): object
    {
        Category::find($id)->update([Category::ACTIVE => $status]);

        return (object) ([
            Category::ID     => $id,
            Category::ACTIVE => $status,
        ]);
    }

    private function getBaseQuery(): Builder
    {
        return Category::with('department')
            ->select(
                Category::tableField(Category::ID),
                Category::tableField(Category::DEPARTMENT_ID),
                Category::tableField(Category::NAME),
                Category::tableField(Category::DESCRIPTION),
                Category::tableField(Category::ACTIVE),
                Category::tableField(Category::CREATED_AT),
            );
    }

    private function getColumnName(PaginationOrder $paginationOrder): string
    {
        return match ($paginationOrder->getColumnName())
        {
            Category::NAME   => Category::tableField(Category::NAME),
            Category::ACTIVE => Category::tableField(Category::ACTIVE),
            default          => Category::tableField(Category::CREATED_AT)
        };
    }
}
