<?php

namespace App\Modules\Store\Products\DTO;

use App\Features\General\Images\DTO\ImagesDTO;

class ProductsDTO
{
    public ?string $id;
    public string  $productName;
    public ?string $productDescription;
    public string  $productUniqueName;
    public string  $productCode;
    public float   $value;
    public int     $quantity;
    public int     $balance;
    public ?array  $categoriesId;
    public ?array  $imageLinks;
    public bool    $highlightProduct;

    public function __construct(
        public ImagesDTO $imagesDTO
    ) {}
}
