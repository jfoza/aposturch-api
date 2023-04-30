<?php

namespace App\Modules\Membership\Church\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\Service;
use App\Features\Base\Traits\DispatchExceptionTrait;
use App\Features\City\Cities\Contracts\CityRepositoryInterface;
use App\Features\City\Cities\Validations\CityValidations;
use App\Features\Users\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Modules\Membership\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Membership\Church\Contracts\CreateChurchServiceInterface;
use App\Modules\Membership\Church\DTO\ChurchDTO;
use App\Modules\Membership\Church\Models\Church;
use App\Modules\Membership\Church\Validations\ChurchValidations;
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

        $churchDTO->adminUsersFiltersDTO->profileUniqueName = [ProfileUniqueNameEnum::ADMIN_CHURCH->value];

        ChurchValidations::isValidAdminsChurch(
            $this->adminUsersRepository,
            $churchDTO->adminUsersFiltersDTO
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

            $this->churchRepository->saveResponsible($created['id'], $churchDTO->adminUsersFiltersDTO->adminsId);

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
