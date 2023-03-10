<?php

namespace App\Features\Users\Rules\Contracts;

interface RulesRepositoryInterface
{
    public function findAllByUserIdAndModulesId(string $userId, array|null $userModulesId);
}
