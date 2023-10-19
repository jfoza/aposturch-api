<?php

namespace App\Modules\Store\Categories\Repositories;

use App\Base\Http\Pagination\PaginationOrder;
use App\Base\Traits\BuilderTrait;
use App\Modules\Store\Categories\Contracts\CategoriesRepositoryInterface;
use App\Modules\Store\Categories\DTO\CategoriesDTO;
use App\Modules\Store\Categories\DTO\CategoriesFiltersDTO;
use App\Modules\Store\Categories\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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
                    Category::NAME,
                    'ilike',
                    "%$categoriesFiltersDTO->name%"
                )
            )
            ->when(
                isset($categoriesFiltersDTO->hasSubcategories),
                function ($q) use($categoriesFiltersDTO) {
                    if($categoriesFiltersDTO->hasSubcategories === true)
                    {
                        return $q->withCount('subcategory')->has('subcategory', '>', 0);
                    }

                    return $q->withCount('subcategory')->has('subcategory', '=', 0);
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

    public function findById(string $id): ?object
    {
        return $this->getBaseQuery()->find($id);
    }

    public function findByName(string $name): ?object
    {
        return $this->getBaseQuery()->where(Category::NAME, $name)->first();
    }

    public function create(CategoriesDTO $categoriesDTO): object
    {
        return Category::create([
            Category::NAME        => $categoriesDTO->name,
            Category::DESCRIPTION => $categoriesDTO->description,
        ]);
    }

    public function save(CategoriesDTO $categoriesDTO): object
    {
        $update = [
            Category::ID          => $categoriesDTO->id,
            Category::NAME        => $categoriesDTO->name,
            Category::DESCRIPTION => $categoriesDTO->description,
        ];

        Category::where(Category::ID, $categoriesDTO->id)->update($update);

        return (object) ($update);
    }

    public function updateStatus(string $id, bool $status): object
    {
        Category::find($id)->update([Category::ACTIVE => $status]);

        return (object) ([
            Category::ID     => $id,
            Category::ACTIVE => $status,
        ]);
    }

    public function remove(string $id): void
    {
        Category::find($id)->delete();
    }

    private function getBaseQuery()
    {
        return Category::select(
            Category::ID,
            Category::NAME,
            Category::DESCRIPTION,
            Category::ACTIVE,
            Category::CREATED_AT,
        );
    }

    private function getColumnName(PaginationOrder $paginationOrder): string
    {
        return match ($paginationOrder->getColumnName())
        {
            Category::NAME       => Category::tableField(Category::NAME),
            Category::ACTIVE     => Category::tableField(Category::ACTIVE),
            default              => Category::tableField(Category::CREATED_AT)
        };
    }
}
