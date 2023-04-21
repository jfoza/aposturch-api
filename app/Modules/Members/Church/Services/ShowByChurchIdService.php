<?php

namespace App\Modules\Members\Church\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\Service;
use App\Modules\Members\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Members\Church\Contracts\ShowByChurchIdServiceInterface;
use App\Modules\Members\Church\Models\Church;
use App\Modules\Members\Church\Validations\ChurchValidations;
use App\Shared\Enums\RulesEnum;
use App\Shared\Helpers\Helpers;

class ShowByChurchIdService extends Service implements ShowByChurchIdServiceInterface
{
    private string $churchId;

    public function __construct(
        private readonly ChurchRepositoryInterface $churchRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(string $churchId): object
    {
        $this->churchId = $churchId;

        $policy = $this->getPolicy();

        $church = match (true) {
            $policy->haveRule(RulesEnum::MEMBERS_MODULE_CHURCH_ADMIN_MASTER_VIEW->value) => $this->showByAdminMaster(),
            $policy->haveRule(RulesEnum::MEMBERS_MODULE_CHURCH_ADMIN_CHURCH_VIEW->value) => $this->showByAdminChurch(),

            default => $policy->dispatchErrorForbidden(),
        };

        if(count($church->imagesChurch) > 0)
        {
            $church->image = $church->imagesChurch->first();

            $church->image->path = Helpers::getApiUrl("storage/{$church->image->path}");
        }

        return $church;
    }

    /**
     * @throws AppException
     */
    private function showByAdminMaster(): ?object
    {
        return ChurchValidations::churchIdExists(
            $this->churchRepository,
            $this->churchId
        );
    }

    /**
     * @throws AppException
     */
    private function showByAdminChurch(): ?object
    {
        $church = ChurchValidations::churchIdExists(
            $this->churchRepository,
            $this->churchId
        );

        $this->userHasChurch(
            Church::ID,
            $this->churchId
        );

        return $church;
    }
}
