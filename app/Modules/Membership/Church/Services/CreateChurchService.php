<?php

namespace App\Modules\Membership\Church\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\Service;
use App\Features\Base\Traits\DispatchExceptionTrait;
use App\Features\City\Cities\Contracts\CityRepositoryInterface;
use App\Features\City\Cities\Validations\CityValidations;
use App\Modules\Membership\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Membership\Church\Contracts\CreateChurchServiceInterface;
use App\Modules\Membership\Church\DTO\ChurchDTO;
use App\Modules\Membership\Church\Traits\ResponsibleMemberValidationsTrait;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Shared\Enums\RulesEnum;
use App\Shared\Helpers\Helpers;
use App\Shared\Utils\Transaction;

class CreateChurchService extends Service implements CreateChurchServiceInterface
{
    use DispatchExceptionTrait;
    use ResponsibleMemberValidationsTrait;

    public function __construct(
        private readonly ChurchRepositoryInterface $churchRepository,
        private readonly CityRepositoryInterface   $cityRepository,
        private readonly MembersRepositoryInterface $membersRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(ChurchDTO $churchDTO): object
    {
        $this->getPolicy()->havePermission(RulesEnum::MEMBERSHIP_MODULE_CHURCH_ADMIN_MASTER_INSERT->value);

        $this->isValidMembersResponsible(
            $churchDTO->responsibleMembers,
            $this->membersRepository,
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

            $this->churchRepository->saveResponsible($created['id'], $churchDTO->responsibleMembers);

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
