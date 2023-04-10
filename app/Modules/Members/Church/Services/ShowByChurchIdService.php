<?php

namespace App\Modules\Members\Church\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\Service;
use App\Modules\Members\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Members\Church\Contracts\ShowByChurchIdServiceInterface;
use App\Modules\Members\Church\Models\Church;
use App\Modules\Members\Church\Validations\ChurchValidations;
use App\Shared\Enums\RulesEnum;

class ShowByChurchIdService extends Service implements ShowByChurchIdServiceInterface
{
    public function __construct(
        private readonly ChurchRepositoryInterface $churchRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(string $churchId): ?Church
    {
        $this->getPolicy()->havePermission(RulesEnum::MEMBERS_MODULE_CHURCH_VIEW->value);

        return ChurchValidations::churchIdExists(
            $this->churchRepository,
            $churchId
        );
    }
}
