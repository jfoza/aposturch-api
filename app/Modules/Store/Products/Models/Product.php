<?php

namespace App\Modules\Store\Products\Models;

use App\Base\Infra\Models\Register;
use App\Modules\Store\ProductsSubcategories\Models\ProductSubcategory;
use App\Modules\Store\Subcategories\Models\Subcategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Register
{
    use HasFactory;

    const ID                  = 'id';
    const PRODUCT_NAME        = 'product_name';
    const PRODUCT_DESCRIPTION = 'product_description';
    const PRODUCT_UNIQUE_NAME = 'product_unique_name';
    const PRODUCT_VALUE       = 'product_value';
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
        self::PRODUCT_VALUE,
        self::QUANTITY,
        self::BALANCE,
        self::HIGHLIGHT_PRODUCT,
    ];

    public function subcategory(): BelongsToMany
    {
        return $this->belongsToMany(
            Subcategory::class,
            ProductSubcategory::tableName(),
            ProductSubcategory::PRODUCT_ID,
            ProductSubcategory::SUBCATEGORY_ID,
        );
    }
}
