<?php

namespace Tests\Unit\App\Resources;

use App\Features\Module\Modules\Models\Module;
use App\Shared\Enums\ModulesUniqueNameEnum;
use Illuminate\Support\Collection;

class ModulesLists
{
    public static function getModulesByIdInCreateMembers(string $moduleId): Collection
    {
        return collect([
            (object) ([
                Module::ID => $moduleId,
                Module::MODULE_UNIQUE_NAME => ModulesUniqueNameEnum::MEMBERSHIP->value,
                Module::MODULE_DESCRIPTION => 'Membresia',
            ])
        ]);
    }
}
