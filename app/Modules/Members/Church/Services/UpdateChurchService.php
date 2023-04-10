<?php

namespace App\Modules\Members\Church\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\Service;
use App\Features\Base\Traits\DispatchExceptionTrait;
use App\Features\City\Cities\Contracts\CityRepositoryInterface;
use App\Features\City\Cities\Validations\CityValidations;
use App\Modules\Members\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Members\Church\Contracts\UpdateChurchServiceInterface;
use App\Modules\Members\Church\DTO\ChurchDTO;
use App\Modules\Members\Church\Models\Church;
use App\Modules\Members\Church\Validations\ChurchValidations;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class UpdateChurchService extends Service implements UpdateChurchServiceInterface
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
        $this->getPolicy()->havePermission(RulesEnum::MEMBERS_MODULE_CHURCH_UPDATE->value);

        ChurchValidations::churchIdExists(
            $this->churchRepository,
            $churchDTO->id
        );

        CityValidations::cityIdExists(
            $this->cityRepository,
            $churchDTO->cityId
        );

        Transaction::beginTransaction();

        try
        {
            $updated = $this->churchRepository->save($churchDTO);

            Transaction::commit();
        }
        catch (\Exception $e)
        {
            Transaction::rollback();

            $this->dispatchException($e);
        }

        return $updated;
    }
}
