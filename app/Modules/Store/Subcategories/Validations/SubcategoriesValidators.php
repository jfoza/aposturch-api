<?php

namespace App\Modules\Store\Subcategories\Validations;

use App\Exceptions\AppException;
use App\Modules\Store\Departments\Contracts\DepartmentsRepositoryInterface;
use App\Modules\Store\Departments\Models\Department;
use App\Modules\Store\Products\Contracts\ProductsRepositoryInterface;
use App\Modules\Store\Subcategories\Contracts\SubcategoriesRepositoryInterface;
use App\Modules\Store\Subcategories\Models\Subcategory;
use App\Shared\Enums\MessagesEnum;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class SubcategoriesValidators
{
    /**
     * @throws AppException
     */
    public static function subcategoryExists(
        string $id,
        SubcategoriesRepositoryInterface $subcategoriesRepository,
        bool $getProducts = false
    ): object
    {
        if(!$subcategory = $subcategoriesRepository->findById($id, $getProducts))
        {
            throw new AppException(
                MessagesEnum::SUBCATEGORY_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        return $subcategory;
    }

    /**
     * @throws AppException
     */
    public static function subcategoryExistsByName(
        string $name,
        SubcategoriesRepositoryInterface $subcategoriesRepository
    ): void
    {
        if($subcategoriesRepository->findByName($name))
        {
            throw new AppException(
                MessagesEnum::SUBCATEGORY_NAME_ALREADY_EXISTS,
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * @throws AppException
     */
    public static function subcategoryExistsByNameInUpdate(
        string $id,
        string $name,
        SubcategoriesRepositoryInterface $subcategoriesRepository
    ): void
    {
        $subcategory = $subcategoriesRepository->findByName($name);

        if($subcategory && $subcategory->id != $id)
        {
            throw new AppException(
                MessagesEnum::SUBCATEGORY_NAME_ALREADY_EXISTS,
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * @throws AppException
     */
    public static function subcategoriesExists(
        array $subcategoriesIdPayload,
        SubcategoriesRepositoryInterface $subcategoriesRepository
    ): Collection
    {
        $subcategories = $subcategoriesRepository->findAllByIds($subcategoriesIdPayload);

        $subcategoriesId = $subcategories->pluck(Subcategory::ID)->toArray();

        foreach ($subcategoriesIdPayload as $subcategoryIdPayload)
        {
            if(!in_array($subcategoryIdPayload, $subcategoriesId))
            {
                throw new AppException(
                    MessagesEnum::SUBCATEGORY_NOT_FOUND,
                    Response::HTTP_NOT_FOUND
                );
            }
        }

        return $subcategories;
    }

    /**
     * @throws AppException
     */
    public static function hasProducts(
        string $subcategoryId,
        ProductsRepositoryInterface $productsRepository
    ): void
    {
        $products = $productsRepository->findBySubcategory($subcategoryId);

        if($products->isNotEmpty())
        {
            throw new AppException(
                MessagesEnum::SUBCATEGORY_HAS_PRODUCTS,
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}
