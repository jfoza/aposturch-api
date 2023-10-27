<?php

namespace App\Modules\Store\Departments\Models;

use App\Base\Infra\Models\Register;
use App\Modules\Store\Subcategories\Models\Subcategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Register
{
    use HasFactory;

    const ID          = 'id';
    const NAME        = 'name';
    const DESCRIPTION = 'description';
    const ACTIVE      = 'active';
    const CREATED_AT  = 'created_at';

    protected $table = 'store.departments';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';

    protected $fillable = [
        self::NAME,
        self::DESCRIPTION,
        self::ACTIVE,
    ];

    public function subcategory(): HasMany
    {
        return $this->hasMany(Subcategory::class);
    }
}
