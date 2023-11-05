<?php

namespace App\Features\Module\Modules\Repositories;

use App\Features\Module\Modules\Contracts\ModulesRepositoryInterface;
use App\Features\Module\Modules\Models\Module;
use App\Shared\Enums\ModulesUniqueNameEnum;

class ModulesRepository implements ModulesRepositoryInterface
{
    public function findByModulesIdInCreateMembers(array $modulesId)
    {
        $modulesNotAllowed = [
            [Module::MODULE_UNIQUE_NAME, '!=', ModulesUniqueNameEnum::USERS->value]
        ];

        return Module::whereIn(Module::ID, $modulesId)->where($modulesNotAllowed)->get();
    }
}
