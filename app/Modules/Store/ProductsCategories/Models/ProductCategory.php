<?php

namespace App\Modules\Store\ProductsCategories\Models;

use App\Base\Infra\Models\Register;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductCategory extends Register
{
    use HasFactory;

    const ID          = 'id';
    const PRODUCT_ID  = 'product_id';
    const CATEGORY_ID = 'category_id';

    protected $table = 'store.products_categories';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';
}
