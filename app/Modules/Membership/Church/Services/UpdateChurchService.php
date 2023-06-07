<?php

namespace App\Modules\Membership\Church\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\AuthenticatedService;
use App\Features\Base\Traits\EnvironmentException;
use App\Features\City\Cities\Contracts\CityRepositoryInterface;
use App\Features\City\Cities\Validations\CityValidations;
use App\Modules\Membership\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Membership\Church\Contracts\UpdateChurchServiceInterface;
use App\Modules\Membership\Church\DTO\ChurchDTO;
use App\Modules\Membership\Church\Models\Church;
use App\Modules\Membership\Church\Validations\ChurchValidations;
use App\Shared\Enums\RulesEnum;
use App\Shared\Helpers\Helpers;
use App\Shared\Utils\Transaction;

class UpdateChurchService extends AuthenticatedService implements UpdateChurchServiceInterface
{
    private ChurchDTO $churchDTO;

    public function __construct(
        private readonly ChurchRepositoryInterface $churchRepository,
        private readonly CityRepositoryInterface   $cityRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(ChurchDTO $churchDTO): Church
    {
        $this->churchDTO = $churchDTO;

        $policy = $this->getPolicy();

        return match (true)
        {
            $policy->haveRule(RulesEnum::MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_UPDATE->value) => $this->updateByAdminMaster(),
            $policy->haveRule(RulesEnum::MEMBERSHIP_MODULE_CHURCH_ADMIN_CHURCH_UPDATE->value) => $this->updateByAdminChurch(),

            default => $policy->dispatchForbiddenError()
        };
    }

    /**
     * @throws AppException
     */
    private function updateByAdminMaster(): ?Church
    {
        $this->handleValidations();

        return $this->baseUpdateOperation(true);
    }

    /**
     * @throws AppException
     */
    private function updateByAdminChurch(): ?Church
    {
        $this->handleValidations();

        ChurchValidations::memberHasChurchById(
            $this->churchDTO->id,
            $this->getChurchesUserMember()
        );

        return $this->baseUpdateOperation();
    }

    /**
     * @throws AppException
     */
    private function handleValidations()
    {
        ChurchValidations::churchIdExists(
            $this->churchRepository,
            $this->churchDTO->id
        );

        CityValidations::cityIdExists(
            $this->cityRepository,
            $this->churchDTO->cityId
        );

        $this->churchDTO->uniqueName = Helpers::stringUniqueName($this->churchDTO->name);
    }

    /**
     * @throws AppException
     */
    private function baseUpdateOperation()
    {
        Transaction::beginTransaction();

        try
        {
            $updated = $this->churchRepository->save($this->churchDTO);

            Transaction::commit();

            return $updated;
        }
        catch (\Exception $e)
        {
            Transaction::rollback();

            EnvironmentException::dispatchException($e);
        }
    }
}
