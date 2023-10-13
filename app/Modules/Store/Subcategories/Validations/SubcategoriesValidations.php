<?php

namespace App\Modules\Store\Subcategories\Validations;

use App\Exceptions\AppException;
use App\Modules\Store\Subcategories\Contracts\SubcategoriesRepositoryInterface;
use App\Shared\Enums\MessagesEnum;
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
}
