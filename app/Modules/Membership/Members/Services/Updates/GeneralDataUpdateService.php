<?php

namespace App\Modules\Membership\Members\Services\Updates;

use App\Exceptions\AppException;
use App\Features\Base\Services\AuthenticatedService;
use App\Features\Base\Traits\EnvironmentException;
use App\Features\Persons\Contracts\PersonsRepositoryInterface;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\Contracts\Updates\GeneralDataUpdateServiceInterface;
use App\Modules\Membership\Members\DTO\GeneralDataUpdateDTO;
use App\Modules\Membership\Members\DTO\MembersFiltersDTO;
use App\Modules\Membership\Members\Responses\UpdateMemberResponse;
use App\Modules\Membership\Members\Validations\MembersValidations;
use App\Shared\Cache\PolicyCache;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class GeneralDataUpdateService extends AuthenticatedService implements GeneralDataUpdateServiceInterface
{
    private GeneralDataUpdateDTO $generalDataUpdateDTO;
    private mixed $userMember;

    public function __construct(
        private readonly PersonsRepositoryInterface  $personsRepository,
        private readonly UsersRepositoryInterface    $usersRepository,
        private readonly MembersRepositoryInterface  $membersRepository,
        private readonly MembersFiltersDTO           $membersFiltersDTO,
        private readonly UpdateMemberResponse        $updateMemberResponse,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(GeneralDataUpdateDTO $generalDataUpdateDTO): UpdateMemberResponse
    {
        $policy = $this->getPolicy();

        $this->generalDataUpdateDTO = $generalDataUpdateDTO;

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
        if(!$this->userPayloadIsEqualsAuthUser($this->generalDataUpdateDTO->id))
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
        if(!$this->userPayloadIsEqualsAuthUser($this->generalDataUpdateDTO->id))
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
        if(!$this->userPayloadIsEqualsAuthUser($this->generalDataUpdateDTO->id))
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
        if(!$this->userMember = $this->membersRepository->findOneByFilters($this->generalDataUpdateDTO->id, $this->membersFiltersDTO))
        {
            throw new AppException(
                MessagesEnum::USER_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        MembersValidations::emailAlreadyExistsInUpdate(
            $this->generalDataUpdateDTO->id,
            $this->generalDataUpdateDTO->email,
            $this->usersRepository,
        );

        MembersValidations::phoneAlreadyExistsInUpdate(
            $this->generalDataUpdateDTO->id,
            $this->generalDataUpdateDTO->phone,
            $this->usersRepository,
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

            $person = $this->personsRepository->savePhone($personId, $this->generalDataUpdateDTO->phone);
            $user = $this->usersRepository->saveInMembers($this->generalDataUpdateDTO);

            PolicyCache::invalidatePolicy($this->generalDataUpdateDTO->id);

            $this->updateMemberResponse->id     = $user->id;
            $this->updateMemberResponse->name   = $user->name;
            $this->updateMemberResponse->email  = $user->email;
            $this->updateMemberResponse->phone  = $person->phone;

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
