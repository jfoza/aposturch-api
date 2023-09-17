<?php

namespace App\Features\Module\Modules\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\AuthenticatedService;
use App\Features\Module\Modules\Contracts\FindAllModulesByUserLoggedServiceInterface;
use App\Shared\Enums\ModulesUniqueNameEnum;
use App\Shared\Enums\RulesEnum;

class FindAllModulesByUserLoggedService extends AuthenticatedService implements FindAllModulesByUserLoggedServiceInterface
{
    /**
     * @throws AppException
     */
    public function execute(): array
    {
        $this->getPolicy()->havePermission(RulesEnum::MODULES_VIEW->value);

        $activeModules = $this->getModulesUser()->filter(
            fn(object $value) => $value->active == true && $value->module_unique_name != ModulesUniqueNameEnum::USERS->value
        );

        return array_values($activeModules->all());
    }
}
