<?php

namespace App\Features\Module\Modules\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\Service;
use App\Features\Module\Modules\Contracts\FindAllModulesByUserLoggedServiceInterface;
use App\Shared\Enums\RulesEnum;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;

class FindAllModulesByUserLoggedService extends Service implements FindAllModulesByUserLoggedServiceInterface
{
    /**
     * @throws UserNotDefinedException|AppException
     */
    public function execute(): array
    {
        $this->getPolicy()->havePermission(RulesEnum::MODULES_VIEW->value);

        $activeModules = $this->getModulesUserMember()->filter(
            fn(object $value) => $value->active == true
        );

        return $activeModules->all();
    }
}
