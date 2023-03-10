<?php

namespace App\Features\Module\Modules\Traits;

use App\Features\Module\Modules\Infra\Models\Module;

trait ModulesTrait
{
    public function getModulesIdByUser(mixed $user)
    {
        $modulesId = [];

        if($modules = $user->module)
        {
            $modulesId = $modules->pluck(Module::ID)->toArray();
        }

        return $modulesId;
    }
}
