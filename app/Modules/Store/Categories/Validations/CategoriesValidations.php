<?php

namespace App\Modules\Store\Categories\Validations;

use App\Exceptions\AppException;
use App\Modules\Store\Categories\Contracts\CategoriesRepositoryInterface;
use App\Modules\Store\Categories\Models\Category;
use App\Modules\Store\Subcategories\Contracts\SubcategoriesRepositoryInterface;
use App\Shared\Enums\MessagesEnum;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class CategoriesValidations
{
    /**
     * @throws AppException
     */
    public static function categoryExists(
        string $id,
        CategoriesRepositoryInterface $categoriesRepository
    ): object
    {
        if(!$category = $categoriesRepository->findById($id))
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
    public static function hasSubcategories(
        string $categoryId,
        SubcategoriesRepositoryInterface $subcategoriesRepository
    ): void
    {
        $subcategories = $subcategoriesRepository->findByCategory($categoryId);

        if($subcategories->isNotEmpty())
        {
            throw new AppException(
                MessagesEnum::CATEGORY_HAS_SUBCATEGORIES,
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

        $notFound = [];

        foreach ($categoriesIdPayload as $categoryIdPayload)
        {
            if(!in_array($categoryIdPayload, $categoriesId))
            {
                $notFound[] = $categoryIdPayload;
            }
        }

        if(!empty($notFound))
        {
            throw new AppException(
                MessagesEnum::CATEGORY_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        return $categories;
    }
}
