<?php

namespace App\Modules\Store\Products\Models;

use App\Base\Infra\Models\Register;
use App\Modules\Store\ProductsCategories\Models\ProductCategory;
use App\Modules\Store\Categories\Models\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Register
{
    use HasFactory;

    const ID                  = 'id';
    const PRODUCT_NAME        = 'product_name';
    const PRODUCT_DESCRIPTION = 'product_description';
    const PRODUCT_UNIQUE_NAME = 'product_unique_name';
    const PRODUCT_CODE        = 'product_code';
    const VALUE               = 'value';
    const QUANTITY            = 'quantity';
    const BALANCE             = 'balance';
    const HIGHLIGHT_PRODUCT   = 'highlight_product';
    const ACTIVE              = 'active';
    const CREATED_AT          = 'created_at';

    protected $table = 'store.products';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';

    protected $fillable = [
        self::PRODUCT_NAME,
        self::PRODUCT_DESCRIPTION,
        self::PRODUCT_UNIQUE_NAME,
        self::PRODUCT_CODE,
        self::VALUE,
        self::QUANTITY,
        self::BALANCE,
        self::HIGHLIGHT_PRODUCT,
    ];

    public function category(): BelongsToMany
    {
        return $this->belongsToMany(
            Category::class,
            ProductCategory::tableName(),
            ProductCategory::PRODUCT_ID,
            ProductCategory::CATEGORY_ID,
        );
    }
}
