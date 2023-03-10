<?php

namespace App\Features\Module\Modules\Infra\Models;

use App\Features\Base\Infra\Models\Register;
use App\Features\Users\ModulesUsers\Infra\Models\ModuleUser;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Module extends Register
{
    const ID          = 'id';
    const DESCRIPTION = 'description';
    const ACTIVE      = 'active';

    protected $table = 'module.modules';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';

    protected $fillable = [
        self::DESCRIPTION,
        self::ACTIVE,
    ];

    public function user(): BelongsToMany
    {
        return $this->belongsToMany(
            Module::class,
            ModuleUser::tableName(),
            ModuleUser::MODULE_ID,
            ModuleUser::USER_ID,
        );
    }
}
