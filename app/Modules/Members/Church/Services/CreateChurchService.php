<?php

namespace App\Modules\Members\Church\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\Service;
use App\Features\Base\Traits\DispatchExceptionTrait;
use App\Features\City\Cities\Contracts\CityRepositoryInterface;
use App\Features\City\Cities\Validations\CityValidations;
use App\Features\Users\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Modules\Members\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Members\Church\Contracts\CreateChurchServiceInterface;
use App\Modules\Members\Church\DTO\ChurchDTO;
use App\Modules\Members\Church\Models\Church;
use App\Modules\Members\Church\Validations\ChurchValidations;
use App\Shared\Enums\RulesEnum;
use App\Shared\Helpers\Helpers;
use App\Shared\Utils\Transaction;
use Illuminate\Support\Collection;

class CreateChurchService extends Service implements CreateChurchServiceInterface
{
    use DispatchExceptionTrait;

    public function __construct(
        private readonly ChurchRepositoryInterface $churchRepository,
        private readonly CityRepositoryInterface   $cityRepository,
        private readonly AdminUsersRepositoryInterface  $adminUsersRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(ChurchDTO $churchDTO): Church|Collection
    {
        $this->getPolicy()->havePermission(RulesEnum::MEMBERS_MODULE_CHURCH_ADMIN_MASTER_INSERT->value);

        ChurchValidations::isValidAdminsChurch(
            $this->adminUsersRepository,
            $churchDTO->responsibleIds
        );

        CityValidations::cityIdExists(
            $this->cityRepository,
            $churchDTO->cityId
        );

        $churchDTO->uniqueName = Helpers::stringUniqueName($churchDTO->name);

        Transaction::beginTransaction();

        try
        {
            $created = $this->churchRepository->create($churchDTO);

            $this->churchRepository->saveResponsible($created['id'], $churchDTO->responsibleIds);

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
