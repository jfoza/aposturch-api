<?php

namespace App\Modules\Membership\Members\Services\Updates;

use App\Exceptions\AppException;
use App\Features\Base\Traits\EnvironmentException;
use App\Features\City\Cities\Contracts\CityRepositoryInterface;
use App\Features\City\Cities\Validations\CityValidations;
use App\Features\Persons\Contracts\PersonsRepositoryInterface;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\Contracts\Updates\AddressDataUpdateServiceInterface;
use App\Modules\Membership\Members\DTO\AddressDataUpdateDTO;
use App\Modules\Membership\Members\Responses\UpdateMemberResponse;
use App\Modules\Membership\Members\Services\MembersBaseService;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;
use Exception;

class AddressDataUpdateService extends MembersBaseService implements AddressDataUpdateServiceInterface
{
    private AddressDataUpdateDTO $addressDataUpdateDTO;
    private mixed $userMember;

    public function __construct(
        protected MembersRepositoryInterface $membersRepository,
        private readonly PersonsRepositoryInterface  $personsRepository,
        private readonly CityRepositoryInterface     $cityRepository,
        private readonly UpdateMemberResponse        $updateMemberResponse,
    ) {
        parent::__construct($this->membersRepository);
    }

    /**
     * @throws AppException
     */
    public function execute(AddressDataUpdateDTO $addressDataUpdateDTO): UpdateMemberResponse
    {
        $policy = $this->getPolicy();

        $this->addressDataUpdateDTO = $addressDataUpdateDTO;

        return match (true)
        {
            $policy->haveRule(RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER_UPDATE->value)
                => $this->updateByAdminMaster(),

            $policy->haveRule(RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_UPDATE->value)
                => $this->updateByAdminChurch(),

            $policy->haveRule(RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_UPDATE->value)
                => $this->updateByAdminModule(),

            $policy->haveRule(RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_UPDATE->value)
                => $this->updateByAssistant(),

            default => $policy->dispatchForbiddenError(),
        };
    }

    /**
     * @throws AppException
     */
    private function updateByAdminMaster(): UpdateMemberResponse
    {
        $this->userMember = $this->findOrFail($this->addressDataUpdateDTO->id);

        $this->handleGeneralValidations();

        return $this->updateMemberData();
    }

    /**
     * @throws AppException
     */
    private function updateByAdminChurch(): UpdateMemberResponse
    {
        $this->userMember = $this->findOrFailWithHierarchy(
            $this->addressDataUpdateDTO->id,
            ProfileUniqueNameEnum::ADMIN_CHURCH->value,
        );

        $this->handleGeneralValidations();

        return $this->updateMemberData();
    }

    /**
     * @throws AppException
     */
    private function updateByAdminModule(): UpdateMemberResponse
    {
        $this->userMember = $this->findOrFailWithHierarchy(
            $this->addressDataUpdateDTO->id,
            ProfileUniqueNameEnum::ADMIN_MODULE->value,
        );

        $this->handleGeneralValidations();

        return $this->updateMemberData();
    }

    /**
     * @throws AppException
     */
    private function updateByAssistant(): UpdateMemberResponse
    {
        $this->userMember = $this->findOrFailWithHierarchy(
            $this->addressDataUpdateDTO->id,
            ProfileUniqueNameEnum::ASSISTANT->value,
        );

        $this->handleGeneralValidations();

        return $this->updateMemberData();
    }

    /**
     * @throws AppException
     */
    private function handleGeneralValidations(): void
    {
        CityValidations::cityIdExists(
            $this->cityRepository,
            $this->addressDataUpdateDTO->cityId
        );
    }

    /**
     * @return UpdateMemberResponse
     * @throws AppException
     */
    public function updateMemberData(): UpdateMemberResponse
    {
        Transaction::beginTransaction();

        try
        {
            $personId = $this->userMember->person_id;

            $person = $this->personsRepository->saveAddress($personId, $this->addressDataUpdateDTO);

            $this->updateMemberResponse->id            = $this->addressDataUpdateDTO->id;
            $this->updateMemberResponse->zipCode       = $person->zip_code;
            $this->updateMemberResponse->address       = $person->address;
            $this->updateMemberResponse->numberAddress = $person->number_address;
            $this->updateMemberResponse->complement    = $person->complement;
            $this->updateMemberResponse->district      = $person->district;
            $this->updateMemberResponse->cityId        = $person->city_id;
            $this->updateMemberResponse->uf            = $person->uf;

            Transaction::commit();

            return $this->updateMemberResponse;
        }
        catch (Exception $e)
        {
            Transaction::rollback();

            EnvironmentException::dispatchException($e);
        }
    }
}
