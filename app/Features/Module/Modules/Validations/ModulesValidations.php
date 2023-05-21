<?php

namespace App\Features\Module\Modules\Validations;

use App\Exceptions\AppException;
use App\Features\Module\Modules\Contracts\ModulesRepositoryInterface;
use App\Features\Module\Modules\Models\Module;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\ModulesUniqueNameEnum;
use Symfony\Component\HttpFoundation\Response;

class ModulesValidations
{
    /**
     * @throws AppException
     */
    public static function validateModulesId(
        array $modulesIdPayload,
        ModulesRepositoryInterface $modulesRepository,
    ): void
    {
        $modulesId = $modulesRepository
            ->findByModulesIdInCreateMembers($modulesIdPayload)
            ->pluck(Module::ID)
            ->toArray();

        $notFound = [];

        foreach ($modulesIdPayload as $moduleIdPayload)
        {
            if(!in_array($moduleIdPayload, $modulesId))
            {
                $notFound[] = $moduleIdPayload;
            }
        }

        if(!empty($notFound))
        {
            throw new AppException(
                MessagesEnum::MODULE_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }
    }
}
