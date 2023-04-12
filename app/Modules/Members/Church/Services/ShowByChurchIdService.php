<?php

namespace App\Modules\Members\Church\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\Service;
use App\Modules\Members\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Members\Church\Contracts\ShowByChurchIdServiceInterface;
use App\Modules\Members\Church\Validations\ChurchValidations;
use App\Shared\Enums\RulesEnum;
use App\Shared\Helpers\Helpers;

class ShowByChurchIdService extends Service implements ShowByChurchIdServiceInterface
{
    public function __construct(
        private readonly ChurchRepositoryInterface $churchRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(string $churchId): object
    {
        $this->getPolicy()->havePermission(RulesEnum::MEMBERS_MODULE_CHURCH_VIEW->value);

        $church = ChurchValidations::churchIdExists(
            $this->churchRepository,
            $churchId
        );

        if(count($church->imagesChurch) > 0)
        {
            $church->image = $church->imagesChurch->first();

            $church->image->path = Helpers::getApiUrl("storage/{$church->image->path}");
        }

        return $church;
    }
}
