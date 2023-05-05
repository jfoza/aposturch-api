<?php

namespace App\Features\Users\Users\Traits;

use App\Features\Module\Modules\Models\Module;
use App\Features\Users\Rules\Contracts\RulesRepositoryInterface;

trait UserAbilityTrait
{
    public function findAllUserAbility(
        mixed $user,
        RulesRepositoryInterface $rulesRepository
    )
    {
        $modulesId = $user->module->pluck(Module::ID)->toArray();

        return $rulesRepository->findAllByUserIdAndModulesId($user->id, $modulesId);
    }
}
