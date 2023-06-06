<?php

namespace App\Modules\Membership\Members\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\Service;
use App\Features\Base\Traits\EnvironmentException;
use App\Features\City\Cities\Contracts\CityRepositoryInterface;
use App\Features\City\Cities\Validations\CityValidations;
use App\Features\Persons\Contracts\PersonsRepositoryInterface;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\DTO\UserDTO;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\Contracts\UpdateMemberServiceInterface;
use App\Modules\Membership\Members\DTO\MembersFiltersDTO;
use App\Modules\Membership\Members\Responses\UpdateMemberResponse;
use App\Modules\Membership\Members\Validations\MembersValidations;
use App\Shared\Cache\PolicyCache;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Auth;
use App\Shared\Utils\Transaction;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;

class UpdateMemberService extends Service implements UpdateMemberServiceInterface
{
    private UserDTO $userDTO;
    private mixed $userMember;

    public function __construct(
        private readonly PersonsRepositoryInterface  $personsRepository,
        private readonly UsersRepositoryInterface    $usersRepository,
        private readonly MembersRepositoryInterface  $membersRepository,
        private readonly CityRepositoryInterface     $cityRepository,
        private readonly MembersFiltersDTO           $membersFiltersDTO,
    ) {}

    /**
     * @throws AppException
     * @throws UserNotDefinedException
     */
    public function execute(UserDTO $userDTO): UpdateMemberResponse
    {
        $policy = $this->getPolicy();

        $this->userDTO = $userDTO;

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
     * @throws UserNotDefinedException
     */
    private function updateByAdminChurch(): UpdateMemberResponse
    {
        if(Auth::getId() != $this->userDTO->id)
        {
            $this->membersFiltersDTO->profileUniqueName = [
                ProfileUniqueNameEnum::ADMIN_MODULE->value,
                ProfileUniqueNameEnum::ASSISTANT->value,
                ProfileUniqueNameEnum::MEMBER->value,
            ];
        }

        $this->handleGeneralValidations();

        MembersValidations::memberUserHasChurch(
            $this->userMember,
            $this->getChurchesUserMember()
        );

        return $this->updateMemberData();
    }

    /**
     * @throws AppException
     * @throws UserNotDefinedException
     */
    private function updateByAdminModule(): UpdateMemberResponse
    {
        if(Auth::getId() != $this->userDTO->id)
        {
            $this->membersFiltersDTO->profileUniqueName = [
                ProfileUniqueNameEnum::ASSISTANT->value,
                ProfileUniqueNameEnum::MEMBER->value,
            ];
        }

        $this->handleGeneralValidations();

        MembersValidations::memberUserHasChurch(
            $this->userMember,
            $this->getChurchesUserMember()
        );

        return $this->updateMemberData();
    }

    /**
     * @throws AppException|UserNotDefinedException
     */
    private function updateByAssistant(): UpdateMemberResponse
    {
        if(Auth::getId() != $this->userDTO->id)
        {
            $this->membersFiltersDTO->profileUniqueName = [
                ProfileUniqueNameEnum::MEMBER->value,
            ];
        }

        $this->handleGeneralValidations();

        MembersValidations::memberUserHasChurch(
            $this->userMember,
            $this->getChurchesUserMember()
        );

        return $this->updateMemberData();
    }

    /**
     * @throws AppException
     */
    private function handleGeneralValidations()
    {
        if(!$this->userMember = $this->membersRepository->findOneByFilters($this->userDTO->id, $this->membersFiltersDTO))
        {
            throw new AppException(
                MessagesEnum::USER_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        MembersValidations::emailAlreadyExistsInUpdate(
            $this->userDTO->id,
            $this->userDTO->email,
            $this->usersRepository,
        );

        MembersValidations::phoneAlreadyExistsInUpdate(
            $this->userDTO->id,
            $this->userDTO->person->phone,
            $this->usersRepository,
        );

        CityValidations::cityIdExists(
            $this->cityRepository,
            $this->userDTO->person->cityId
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
            $this->userDTO->person->id = $this->userMember->person_id;

            $person = $this->personsRepository->save($this->userDTO->person);
            $user = $this->usersRepository->saveInMembers($this->userDTO);

            PolicyCache::invalidatePolicy($this->userDTO->id);

            Transaction::commit();

            return new UpdateMemberResponse(
                $user->id,
                $user->name,
                $user->email,
                $person->phone,
                $person->zip_code,
                $person->address,
                $person->number_address,
                $person->complement,
                $person->district,
                $person->city_id,
                $person->uf,
            );

        }
        catch (Exception $e)
        {
            Transaction::rollback();

            EnvironmentException::dispatchException($e);
        }
    }
}
