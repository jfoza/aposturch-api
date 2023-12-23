<?php

namespace App\Modules\Store\Products\Validations;

use App\Exceptions\AppException;
use App\Modules\Store\Products\Contracts\ProductsRepositoryInterface;
use App\Modules\Store\Products\Models\Product;
use App\Shared\Enums\MessagesEnum;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class ProductsValidators
{
    /**
     * @throws AppException
     */
    public static function productExists(
        string $productId,
        ProductsRepositoryInterface $productsRepository
    ): object
    {
        if(!$product = $productsRepository->findById($productId))
        {
            throw new AppException(
                MessagesEnum::PRODUCT_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        return $product;
    }

    /**
     * @throws AppException
     */
    public static function productExistsByUniqueName(
        string $productUniqueName,
        ProductsRepositoryInterface $productsRepository
    ): object
    {
        if(!$product = $productsRepository->findByUniqueName($productUniqueName))
        {
            throw new AppException(
                MessagesEnum::PRODUCT_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        return $product;
    }

    /**
     * @throws AppException
     */
    public static function productsExists(
        array $productsIdPayload,
        ProductsRepositoryInterface $productsRepository
    ): Collection
    {
        $products = $productsRepository->findAllByIds($productsIdPayload);

        foreach ($productsIdPayload as $productIdPayload)
        {
            if(!$products->where(Product::ID, $productIdPayload)->first())
            {
                throw new AppException(
                    MessagesEnum::PRODUCT_NOT_FOUND,
                    Response::HTTP_NOT_FOUND
                );
            }
        }

        return $products;
    }

    /**
     * @throws AppException
     */
    public static function productExistsByName(
        string $productName,
        ProductsRepositoryInterface $productsRepository
    ): void
    {
        if($productsRepository->findByName($productName))
        {
            throw new AppException(
                MessagesEnum::PRODUCT_NAME_ALREADY_EXISTS,
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * @throws AppException
     */
    public static function productExistsByCode(
        string $code,
        ProductsRepositoryInterface $productsRepository
    ): void
    {
        if($productsRepository->findByCode($code))
        {
            throw new AppException(
                MessagesEnum::PRODUCT_CODE_ALREADY_EXISTS,
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * @throws AppException
     */
    public static function productExistsByNameInUpdate(
        string $id,
        string $productName,
        ProductsRepositoryInterface $productsRepository
    ): void
    {
        $product = $productsRepository->findByName($productName);

        if($product && $product->id != $id)
        {
            throw new AppException(
                MessagesEnum::PRODUCT_NAME_ALREADY_EXISTS,
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * @throws AppException
     */
    public static function productExistsByCodeInUpdate(
        string $id,
        string $code,
        ProductsRepositoryInterface $productsRepository
    ): void
    {
        $product = $productsRepository->findByCode($code);

        if($product && $product->id != $id)
        {
            throw new AppException(
                MessagesEnum::PRODUCT_CODE_ALREADY_EXISTS,
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * @throws AppException
     */
    public static function productQuantityBalanceValidation(
        int $quantity,
        int $balance,
    ): void
    {
        if($balance > $quantity)
        {
            throw new AppException(
                MessagesEnum::BALANCE_IS_GREATER_THAN_THE_AMOUNT,
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}
