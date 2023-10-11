<?php

namespace App\Modules\Membership\Church\Services;

use App\Base\Services\AuthenticatedService;
use App\Base\Traits\EnvironmentException;
use App\Exceptions\AppException;
use App\Features\City\Cities\Contracts\CityRepositoryInterface;
use App\Features\City\Cities\Validations\CityValidations;
use App\Modules\Membership\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Membership\Church\Contracts\CreateChurchServiceInterface;
use App\Modules\Membership\Church\DTO\ChurchDTO;
use App\Shared\Enums\RulesEnum;
use App\Shared\Helpers\Helpers;
use App\Shared\Utils\Transaction;

class CreateChurchService extends AuthenticatedService implements CreateChurchServiceInterface
{
    public function __construct(
        private readonly ChurchRepositoryInterface $churchRepository,
        private readonly CityRepositoryInterface   $cityRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(ChurchDTO $churchDTO): object
    {
        $this->getPolicy()->havePermission(RulesEnum::MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_INSERT->value);

        CityValidations::cityIdExists(
            $this->cityRepository,
            $churchDTO->cityId
        );

        $churchDTO->uniqueName = Helpers::stringUniqueName($churchDTO->name);

        Transaction::beginTransaction();

        try
        {
            $created = $this->churchRepository->create($churchDTO);

            Transaction::commit();
        }
        catch (\Exception $e)
        {
            Transaction::rollback();

            EnvironmentException::dispatchException($e);
        }

        return $created;
    }
}
