<?php

namespace App\Modules\Store\Categories\Validations;

use App\Exceptions\AppException;
use App\Modules\Store\Products\Contracts\ProductsRepositoryInterface;
use App\Modules\Store\Categories\Contracts\CategoriesRepositoryInterface;
use App\Modules\Store\Categories\Models\Category;
use App\Shared\Enums\MessagesEnum;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class CategoriesValidators
{
    /**
     * @throws AppException
     */
    public static function categoryExists(
        string                        $id,
        CategoriesRepositoryInterface $categoriesRepository,
        bool                          $getProducts = false
    ): object
    {
        if(!$category = $categoriesRepository->findById($id, $getProducts))
        {
            throw new AppException(
                MessagesEnum::CATEGORY_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        return $category;
    }

    /**
     * @throws AppException
     */
    public static function categoryExistsByName(
        string $name,
        CategoriesRepositoryInterface $categoriesRepository
    ): void
    {
        if($categoriesRepository->findByName($name))
        {
            throw new AppException(
                MessagesEnum::CATEGORY_NAME_ALREADY_EXISTS,
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * @throws AppException
     */
    public static function categoryExistsByNameInUpdate(
        string $id,
        string $name,
        CategoriesRepositoryInterface $categoriesRepository
    ): void
    {
        $category = $categoriesRepository->findByName($name);

        if($category && $category->id != $id)
        {
            throw new AppException(
                MessagesEnum::CATEGORY_NAME_ALREADY_EXISTS,
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * @throws AppException
     */
    public static function categoriesExists(
        array $categoriesIdPayload,
        CategoriesRepositoryInterface $categoriesRepository
    ): Collection
    {
        $categories = $categoriesRepository->findAllByIds($categoriesIdPayload);

        $categoriesId = $categories->pluck(Category::ID)->toArray();

        foreach ($categoriesIdPayload as $categoryIdPayload)
        {
            if(!in_array($categoryIdPayload, $categoriesId))
            {
                throw new AppException(
                    MessagesEnum::CATEGORY_NOT_FOUND,
                    Response::HTTP_NOT_FOUND
                );
            }
        }

        return $categories;
    }

    /**
     * @throws AppException
     */
    public static function hasProducts(
        string $categoryId,
        ProductsRepositoryInterface $productsRepository
    ): void
    {
        $products = $productsRepository->findByCategory($categoryId);

        if($products->isNotEmpty())
        {
            throw new AppException(
                MessagesEnum::CATEGORY_HAS_PRODUCTS,
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}
