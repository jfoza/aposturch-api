<?php

namespace App\Features\Base\Traits;

use App\Features\Base\Infra\Repositories\PolicyRepository;
use App\Features\Module\Modules\Models\Module;
use App\Shared\ACL\Policy;
use App\Shared\Cache\PolicyCache;
use App\Shared\Utils\Auth;

trait PolicyGenerationTrait
{
    public function generatePolicyUser(): Policy
    {
        $rules = [];

        if($user = Auth::getUser()) {
            $rules = $this->getUserRules($user);
        }

        return new Policy($rules);
    }

    public function getUserRules(mixed $user): mixed
    {
        $modulesId = $user->module->pluck(Module::ID)->toArray();

        return PolicyCache::rememberPolicy(
            $user->id,
            function() use($user, $modulesId) {
                $policyRepository = new PolicyRepository();
                return $policyRepository->findAllPolicyUser($user->id, $modulesId);
            }
        );
    }
}
