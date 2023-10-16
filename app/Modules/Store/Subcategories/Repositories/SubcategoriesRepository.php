<?php

namespace App\Modules\Store\Subcategories\Repositories;

use App\Base\Traits\BuilderTrait;
use App\Modules\Store\Categories\Models\Category;
use App\Modules\Store\Subcategories\Contracts\SubcategoriesRepositoryInterface;
use App\Modules\Store\Subcategories\DTO\SubcategoriesDTO;
use App\Modules\Store\Subcategories\DTO\SubcategoriesFiltersDTO;
use App\Modules\Store\Subcategories\Models\Subcategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class SubcategoriesRepository implements SubcategoriesRepositoryInterface
{
    use BuilderTrait;

    public function findAll(SubcategoriesFiltersDTO $subcategoriesFiltersDTO): LengthAwarePaginator|Collection
    {
        $builder = $this
            ->getBaseQuery()
            ->when(
                isset($subcategoriesFiltersDTO->name),
                fn($q) => $q->where(
                    Subcategory::tableField(Subcategory::NAME),
                    'ilike',
                    "%$subcategoriesFiltersDTO->name%"
                )
            )
            ->when(
                isset($subcategoriesFiltersDTO->categoryId),
                fn($q) => $q->whereHas(
                    'category',
                    fn($c) => $c->whereIn(
                        Category::tableField(Category::ID),
                        $subcategoriesFiltersDTO->categoryId
                    )
                )
            );

        return $this->paginateOrGet(
            $builder,
            $subcategoriesFiltersDTO->paginationOrder
        );
    }

    public function findAllByIds(array $subcategoriesId): Collection
    {
        $subcategories = $this->getBaseQuery()->whereIn(Subcategory::ID, $subcategoriesId)->get();

        return collect($subcategories);
    }

    public function findById(string $id): ?object
    {
       return $this
           ->getBaseQuery()
           ->where(Subcategory::ID, $id)
           ->first();
    }

    public function findByName(string $name): ?object
    {
        return $this
            ->getBaseQuery()
            ->where(Subcategory::NAME, $name)
            ->first();
    }

    public function findByCategory(string $categoryId): Collection
    {
        $subcategories = $this
            ->getBaseQuery()
            ->where(Subcategory::CATEGORY_ID, $categoryId)
            ->get();

        return collect($subcategories);
    }

    public function create(SubcategoriesDTO $subcategoriesDTO): object
    {
        return Subcategory::create([
            Subcategory::CATEGORY_ID => $subcategoriesDTO->categoryId,
            Subcategory::NAME        => $subcategoriesDTO->name,
            Subcategory::DESCRIPTION => $subcategoriesDTO->description,
        ]);
    }

    public function save(SubcategoriesDTO $subcategoriesDTO): object
    {
        $update = [
            Subcategory::ID          => $subcategoriesDTO->id,
            Subcategory::CATEGORY_ID => $subcategoriesDTO->categoryId,
            Subcategory::NAME        => $subcategoriesDTO->name,
            Subcategory::DESCRIPTION => $subcategoriesDTO->description,
        ];

        Subcategory::find($subcategoriesDTO->id)->update($update);

        return (object) ($update);
    }

    public function saveCategory(string $categoryId, array $subcategoriesId): void
    {
        Subcategory::whereIn(Subcategory::ID, $subcategoriesId)->update([Subcategory::CATEGORY_ID => $categoryId]);
    }

    public function remove(string $id): void
    {
        Subcategory::where(Subcategory::ID, $id)->delete();
    }

    private function getBaseQuery(): Builder
    {
        return Subcategory::with('category')
            ->select(
                Subcategory::tableField(Subcategory::ID),
                Subcategory::tableField(Subcategory::CATEGORY_ID),
                Subcategory::tableField(Subcategory::NAME),
                Subcategory::tableField(Subcategory::DESCRIPTION),
                Subcategory::tableField(Subcategory::ACTIVE),
                Subcategory::tableField(Subcategory::CREATED_AT),
            );
    }
}
