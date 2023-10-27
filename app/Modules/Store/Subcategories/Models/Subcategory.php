<?php

namespace App\Modules\Store\Subcategories\Models;

use App\Base\Infra\Models\Register;
use App\Modules\Store\Departments\Models\Department;
use App\Modules\Store\Products\Models\Product;
use App\Modules\Store\ProductsSubcategories\Models\ProductSubcategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subcategory extends Register
{
    use HasFactory;

    const ID            = 'id';
    const DEPARTMENT_ID = 'department_id';
    const NAME          = 'name';
    const DESCRIPTION   = 'description';
    const ACTIVE        = 'active';
    const CREATED_AT    = 'created_at';

    protected $table = 'store.subcategories';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';

    protected $fillable = [
        self::DEPARTMENT_ID,
        self::NAME,
        self::DESCRIPTION,
        self::ACTIVE,
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function product(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            ProductSubcategory::tableName(),
            ProductSubcategory::SUBCATEGORY_ID,
            ProductSubcategory::PRODUCT_ID,
        );
    }
}
