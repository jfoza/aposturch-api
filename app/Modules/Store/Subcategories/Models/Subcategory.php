<?php

namespace App\Modules\Store\Subcategories\Models;

use App\Base\Infra\Models\Register;
use App\Modules\Store\Categories\Models\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subcategory extends Register
{
    use HasFactory;

    const ID          = 'id';
    const CATEGORY_ID = 'category_id';
    const NAME        = 'name';
    const DESCRIPTION = 'description';
    const ACTIVE      = 'active';
    const CREATED_AT  = 'created_at';

    protected $table = 'store.subcategories';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';

    protected $fillable = [
        self::CATEGORY_ID,
        self::NAME,
        self::DESCRIPTION,
        self::ACTIVE,
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
