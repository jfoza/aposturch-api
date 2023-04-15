<?php

namespace App\Modules\Members\Church\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\Service;
use App\Modules\Members\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Members\Church\Contracts\ShowByChurchUniqueNameServiceInterface;
use App\Modules\Members\Church\Validations\ChurchValidations;
use App\Shared\Enums\RulesEnum;
use App\Shared\Helpers\Helpers;

class ShowByChurchUniqueNameService extends Service implements ShowByChurchUniqueNameServiceInterface
{
    private string $churchUniqueName;

    public function __construct(
        private readonly ChurchRepositoryInterface $churchRepository
    ) {}

    /**
     * @throws AppException
     */
    public function execute(string $churchUniqueName): object
    {
        $this->churchUniqueName = $churchUniqueName;

        $policy = $this->getPolicy();

        $church = match (true) {
            $policy->haveRule(RulesEnum::MEMBERS_MODULE_CHURCH_ADMIN_MASTER_DETAILS_VIEW->value) => $this->showByAdminMaster(),
            $policy->haveRule(RulesEnum::MEMBERS_MODULE_CHURCH_ADMIN_CHURCH_DETAILS_VIEW->value) => $this->showByAdminChurch(),

            default  => $policy->dispatchErrorForbidden(),
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
        return ChurchValidations::churchUniqueNameExists(
            $this->churchRepository,
            $this->churchUniqueName
        );
    }

    /**
     * @throws AppException
     */
    private function showByAdminChurch(): ?object
    {
        $church = $this->getChurchUserAuth();

        if($church->unique_name != $this->churchUniqueName)
        {
            $this->getPolicy()->dispatchErrorForbidden();
        }

        return ChurchValidations::churchUniqueNameExists(
            $this->churchRepository,
            $this->churchUniqueName
        );
    }
}
