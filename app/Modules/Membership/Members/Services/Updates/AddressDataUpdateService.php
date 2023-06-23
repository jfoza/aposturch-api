<?php

namespace App\Modules\Membership\Members\Services\Updates;

use App\Exceptions\AppException;
use App\Features\Base\Services\AuthenticatedService;
use App\Features\Base\Traits\EnvironmentException;
use App\Features\City\Cities\Contracts\CityRepositoryInterface;
use App\Features\City\Cities\Validations\CityValidations;
use App\Features\Persons\Contracts\PersonsRepositoryInterface;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\Contracts\Updates\AddressDataUpdateServiceInterface;
use App\Modules\Membership\Members\DTO\AddressDataUpdateDTO;
use App\Modules\Membership\Members\DTO\MembersFiltersDTO;
use App\Modules\Membership\Members\Responses\UpdateMemberResponse;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class AddressDataUpdateService extends AuthenticatedService implements AddressDataUpdateServiceInterface
{
    private AddressDataUpdateDTO $addressDataUpdateDTO;
    private mixed $userMember;

    public function __construct(
        private readonly PersonsRepositoryInterface  $personsRepository,
        private readonly MembersRepositoryInterface  $membersRepository,
        private readonly CityRepositoryInterface     $cityRepository,
        private readonly MembersFiltersDTO           $membersFiltersDTO,
        private readonly UpdateMemberResponse        $updateMemberResponse,
    ) {}

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
        $this->handleGeneralValidations();

        return $this->updateMemberData();
    }

    /**
     * @throws AppException
     */
    private function updateByAdminChurch(): UpdateMemberResponse
    {
        if(!$this->userPayloadIsEqualsAuthUser($this->addressDataUpdateDTO->id))
        {
            $this->membersFiltersDTO->profileUniqueName = [
                ProfileUniqueNameEnum::ADMIN_MODULE->value,
                ProfileUniqueNameEnum::ASSISTANT->value,
                ProfileUniqueNameEnum::MEMBER->value,
            ];
        }

        $this->membersFiltersDTO->churchesId = $this->getUserMemberChurchesId();

        $this->handleGeneralValidations();

        return $this->updateMemberData();
    }

    /**
     * @throws AppException
     */
    private function updateByAdminModule(): UpdateMemberResponse
    {
        if(!$this->userPayloadIsEqualsAuthUser($this->addressDataUpdateDTO->id))
        {
            $this->membersFiltersDTO->profileUniqueName = [
                ProfileUniqueNameEnum::ASSISTANT->value,
                ProfileUniqueNameEnum::MEMBER->value,
            ];
        }

        $this->membersFiltersDTO->churchesId = $this->getUserMemberChurchesId();

        $this->handleGeneralValidations();

        return $this->updateMemberData();
    }

    /**
     * @throws AppException
     */
    private function updateByAssistant(): UpdateMemberResponse
    {
        if(!$this->userPayloadIsEqualsAuthUser($this->addressDataUpdateDTO->id))
        {
            $this->membersFiltersDTO->profileUniqueName = [
                ProfileUniqueNameEnum::MEMBER->value,
            ];
        }

        $this->membersFiltersDTO->churchesId = $this->getUserMemberChurchesId();

        $this->handleGeneralValidations();

        return $this->updateMemberData();
    }

    /**
     * @throws AppException
     */
    private function handleGeneralValidations()
    {
        if(!$this->userMember = $this->membersRepository->findOneByFilters($this->addressDataUpdateDTO->id, $this->membersFiltersDTO))
        {
            throw new AppException(
                MessagesEnum::USER_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

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
