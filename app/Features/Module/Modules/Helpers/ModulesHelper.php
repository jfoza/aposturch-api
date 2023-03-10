<?php

namespace App\Features\Module\Modules\Helpers;

use App\Features\Module\Modules\Infra\Models\Module;

class ModulesHelper
{
    public static function getModulesIdByUser(mixed $user)
    {
        $modulesId = [];

        if($modules = $user->module)
        {
            $modulesId = $modules->pluck(Module::ID)->toArray();
        }

        return $modulesId;
    }
}
