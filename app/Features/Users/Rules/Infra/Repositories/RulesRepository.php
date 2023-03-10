<?php

namespace App\Features\Users\Rules\Infra\Repositories;

use App\Features\Base\Infra\Repositories\PolicyRepository;
use App\Features\Users\Rules\Contracts\RulesRepositoryInterface;
use App\Features\Users\Rules\Infra\Models\Rule;

class RulesRepository extends PolicyRepository implements RulesRepositoryInterface
{
    public function findAllByUserIdAndModulesId(string $userId, array|null $userModulesId)
    {
        $rulesByProfileUserId = $this->findAllByProfileUserId($userId);

        return $this->findAllByModulesUser($userModulesId)
            ->union($rulesByProfileUserId)
            ->groupBy(Rule::tableField(Rule::ID))
            ->get()
            ->toArray();
    }
}
