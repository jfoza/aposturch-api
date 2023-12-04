<?php

namespace App\Modules\Store\ProductsImages\Models;

use App\Base\Infra\Models\Register;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductImage extends Register
{
    use HasFactory;

    const ID         = 'id';
    const PRODUCT_ID = 'product_id';
    const IMAGE_ID   = 'image_id';

    protected $table = 'store.products_images';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';
}
