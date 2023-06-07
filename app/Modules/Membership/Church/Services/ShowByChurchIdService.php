<?php

namespace App\Modules\Membership\Church\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\AuthenticatedService;
use App\Modules\Membership\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Membership\Church\Contracts\ShowByChurchIdServiceInterface;
use App\Modules\Membership\Church\Validations\ChurchValidations;
use App\Shared\Enums\RulesEnum;
use App\Shared\Helpers\Helpers;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;

class ShowByChurchIdService extends AuthenticatedService implements ShowByChurchIdServiceInterface
{
    private string $churchId;

    public function __construct(
        private readonly ChurchRepositoryInterface $churchRepository,
    ) {}

    /**
     * @throws AppException
     * @throws UserNotDefinedException
     */
    public function execute(string $churchId): object
    {
        $this->churchId = $churchId;

        $policy = $this->getPolicy();

        $church = match (true) {
            $policy->haveRule(RulesEnum::MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_VIEW->value) => $this->showByAdminMaster(),
            $policy->haveRule(RulesEnum::MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH_VIEW->value) => $this->showByAdminChurch(),

            default => $policy->dispatchForbiddenError(),
        };

        $church->image = null;

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

        ChurchValidations::memberHasChurchById(
            $church->id,
            $this->getChurchesUserMember()
        );

        return $church;
    }
}
