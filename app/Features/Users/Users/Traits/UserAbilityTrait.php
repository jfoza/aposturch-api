<?php

namespace App\Features\Users\Users\Traits;

use App\Features\Module\Modules\Helpers\ModulesHelper;
use App\Features\Users\Rules\Contracts\RulesRepositoryInterface;

trait UserAbilityTrait
{
    public function findAllUserAbility(
        mixed $user,
        RulesRepositoryInterface $rulesRepository
    )
    {
        $modulesId = ModulesHelper::getModulesIdByUser($user);

        return $rulesRepository->findAllByUserIdAndModulesId($user->id, $modulesId);
    }
}
