<?php

namespace App\Modules\Members\Church\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\Service;
use App\Features\Base\Traits\DispatchExceptionTrait;
use App\Features\City\Cities\Contracts\CityRepositoryInterface;
use App\Features\City\Cities\Validations\CityValidations;
use App\Modules\Members\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Members\Church\Contracts\CreateChurchServiceInterface;
use App\Modules\Members\Church\DTO\ChurchDTO;
use App\Modules\Members\Church\Models\Church;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class CreateChurchService extends Service implements CreateChurchServiceInterface
{
    use DispatchExceptionTrait;

    public function __construct(
        private readonly ChurchRepositoryInterface $churchRepository,
        private readonly CityRepositoryInterface   $cityRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(ChurchDTO $churchDTO): Church
    {
        $this->getPolicy()->havePermission(RulesEnum::MEMBERS_MODULE_CHURCH_INSERT->value);

        CityValidations::cityIdExists(
            $this->cityRepository,
            $churchDTO->cityId
        );

        Transaction::beginTransaction();

        try
        {
            $created = $this->churchRepository->create($churchDTO);

            Transaction::commit();
        }
        catch (\Exception $e)
        {
            Transaction::rollback();

            $this->dispatchException($e);
        }

        return $created;
    }
}
