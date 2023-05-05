<?php

namespace App\Modules\Membership\Church\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\Service;
use App\Modules\Membership\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Membership\Church\Contracts\ShowByChurchUniqueNameServiceInterface;
use App\Modules\Membership\Church\Validations\ChurchValidations;
use App\Shared\Enums\RulesEnum;
use App\Shared\Helpers\Helpers;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;

class ShowByChurchUniqueNameService extends Service implements ShowByChurchUniqueNameServiceInterface
{
    private string $churchUniqueName;

    public function __construct(
        private readonly ChurchRepositoryInterface $churchRepository
    ) {}

    /**
     * @throws AppException
     * @throws UserNotDefinedException
     */
    public function execute(string $churchUniqueName): object
    {
        $this->churchUniqueName = $churchUniqueName;

        $policy = $this->getPolicy();

        $church = match (true) {
            $policy->haveRule(RulesEnum::MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_DETAILS_VIEW->value) => $this->showByAdminMaster(),
            $policy->haveRule(RulesEnum::MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH_DETAILS_VIEW->value) => $this->showByAdminChurch(),

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
     * @throws UserNotDefinedException
     */
    private function showByAdminChurch(): ?object
    {
        $church = ChurchValidations::churchUniqueNameExists(
            $this->churchRepository,
            $this->churchUniqueName
        );

        ChurchValidations::memberHasChurchByUniqueName(
            $church->unique_name,
            $this->getChurchesUserMember()
        );

        return $church;
    }
}
