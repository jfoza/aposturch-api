<?php

namespace App\Modules\Store\ProductsSubcategories\Models;

use App\Base\Infra\Models\Register;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductSubcategory extends Register
{
    use HasFactory;

    const ID             = 'id';
    const PRODUCT_ID     = 'product_id';
    const SUBCATEGORY_ID = 'subcategory_id';

    protected $table = 'store.products_subcategories';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';
}
