<?php

namespace App\Modules\Store\Products\Validations;

use App\Exceptions\AppException;
use App\Modules\Store\Products\Contracts\ProductsRepositoryInterface;
use App\Modules\Store\Products\Models\Product;
use App\Shared\Enums\MessagesEnum;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class ProductsValidations
{
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
}
