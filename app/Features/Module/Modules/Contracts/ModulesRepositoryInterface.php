<?php

namespace App\Features\Module\Modules\Contracts;

interface ModulesRepositoryInterface
{
    public function findByModulesIdInCreateMembers(array $modulesId);
}
