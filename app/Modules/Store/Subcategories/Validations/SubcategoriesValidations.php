<?php

namespace App\Modules\Store\Subcategories\Validations;

use App\Exceptions\AppException;
use App\Modules\Store\Categories\Contracts\CategoriesRepositoryInterface;
use App\Modules\Store\Categories\Models\Category;
use App\Modules\Store\Subcategories\Contracts\SubcategoriesRepositoryInterface;
use App\Modules\Store\Subcategories\Models\Subcategory;
use App\Shared\Enums\MessagesEnum;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class SubcategoriesValidations
{
    /**
     * @throws AppException
     */
    public static function subcategoryExists(
        string $id,
        SubcategoriesRepositoryInterface $subcategoriesRepository
    ): object
    {
        if(!$subcategory = $subcategoriesRepository->findById($id))
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
    public static function subcategoriesHasBeUsed(Collection $subcategories): void
    {
        $cannotBeUsed = $subcategories->where(Subcategory::ID, '!=', null)->toArray();

        if(count($cannotBeUsed) > 0)
        {
            throw new AppException(
                MessagesEnum::SUBCATEGORY_CANNOT_BE_USED,
                Response::HTTP_NOT_FOUND
            );
        }
    }
}
