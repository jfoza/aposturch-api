<?php

namespace App\Modules\Store\Products\DTO;

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
    public ?array  $subcategoriesId;
    public bool    $highlightProduct;
}
