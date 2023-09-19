<?php

namespace App\Modules\Membership\Members\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\AuthenticatedService;
use App\Features\Base\Traits\EnvironmentException;
use App\Features\Base\Validations\ProfileHierarchyValidation;
use App\Features\City\Cities\Contracts\CityRepositoryInterface;
use App\Features\City\Cities\Validations\CityValidations;
use App\Features\Module\Modules\Contracts\ModulesRepositoryInterface;
use App\Features\Module\Modules\Validations\ModulesValidations;
use App\Features\Persons\Contracts\PersonsRepositoryInterface;
use App\Features\Users\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\DTO\UserDTO;
use App\Features\Users\Users\Validations\UsersValidations;
use App\Modules\Membership\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Membership\Church\Validations\ChurchValidations;
use App\Modules\Membership\Members\Contracts\CreateMemberServiceInterface;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\Responses\InsertMemberResponse;
use App\Modules\Membership\Members\Validations\MembersValidations;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Hash;
use App\Shared\Utils\Transaction;
use Exception;

class CreateMemberService extends AuthenticatedService implements CreateMemberServiceInterface
{
    private UserDTO $userDTO;
    private mixed $profile;
    private mixed $church;

    public function __construct(
        private readonly PersonsRepositoryInterface  $personsRepository,
        private readonly UsersRepositoryInterface    $usersRepository,
        private readonly MembersRepositoryInterface  $membersRepository,
        private readonly ChurchRepositoryInterface   $churchRepository,
        private readonly ProfilesRepositoryInterface $profilesRepository,
        private readonly CityRepositoryInterface     $cityRepository,
        private readonly ModulesRepositoryInterface  $modulesRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(UserDTO $userDTO): InsertMemberResponse
    {
        $policy = $this->getPolicy();

        $this->userDTO = $userDTO;

        return match (true)
        {
            $policy->haveRule(RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER_INSERT->value) => $this->createByAdminMaster(),
            $policy->haveRule(RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_INSERT->value) => $this->createByAdminChurch(),
            $policy->haveRule(RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_INSERT->value) => $this->createByAdminModule(),
            $policy->haveRule(RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_INSERT->value)    => $this->createByAssistant(),

            default => $policy->dispatchForbiddenError(),
        };
    }

    /**
     * @throws AppException
     */
    private function createByAdminMaster(): InsertMemberResponse
    {
        $this->handleValidations();

        return $this->createNewMember();
    }

    /**
     * @throws AppException
     */
    private function createByAdminChurch(): InsertMemberResponse
    {
        $this->handleValidations();

        ProfileHierarchyValidation::adminChurchInPersistenceValidation(
            [$this->profile->unique_name]
        );

        $this->canAccessTheChurch([$this->userDTO->member->churchId]);

        return $this->createNewMember();
    }

    /**
     * @throws AppException
     */
    private function createByAdminModule(): InsertMemberResponse
    {
        $this->handleValidations();

        ProfileHierarchyValidation::adminModuleInPersistenceValidation(
            [$this->profile->unique_name]
        );

        $this->canAccessTheChurch([$this->userDTO->member->churchId]);
        $this->canAccessEachModules($this->userDTO->modulesId);

        return $this->createNewMember();
    }

    /**
     * @throws AppException
     */
    private function createByAssistant(): InsertMemberResponse
    {
        $this->handleValidations();

        ProfileHierarchyValidation::assistantInPersistenceValidation(
            [$this->profile->unique_name]
        );

        $this->canAccessTheChurch([$this->userDTO->member->churchId]);
        $this->canAccessEachModules($this->userDTO->modulesId);

        return $this->createNewMember();
    }

    /**
     * @throws AppException
     */
    private function handleValidations(): void
    {
        $this->profile = MembersValidations::profileIsValid(
            $this->userDTO->profileId,
            $this->profilesRepository
        );

        ModulesValidations::validateModulesId(
            $this->userDTO->modulesId,
            $this->modulesRepository
        );

        UsersValidations::emailAlreadyExists(
            $this->usersRepository,
            $this->userDTO->email
        );

        UsersValidations::phoneAlreadyExists(
            $this->usersRepository,
            $this->userDTO->person->phone
        );

        $this->church = ChurchValidations::churchIdExists(
            $this->churchRepository,
            $this->userDTO->member->churchId
        );

        CityValidations::cityIdExists(
            $this->cityRepository,
            $this->userDTO->person->cityId
        );
    }

    /**
     * @return InsertMemberResponse
     * @throws AppException
     */
    private function createNewMember(): InsertMemberResponse
    {
        Transaction::beginTransaction();

        try
        {
            $this->userDTO->passwordDTO->encryptedPassword = Hash::generateHash($this->userDTO->passwordDTO->password);

            $person = $this->personsRepository->create($this->userDTO->person);
            $this->userDTO->personId = $person->id;

            $user = $this->usersRepository->create($this->userDTO, true);
            $this->userDTO->id = $user->id;

            $this->usersRepository->saveProfiles($this->userDTO->id, [$this->userDTO->profileId]);

            $this->userDTO->member->userId = $user->id;

            $member = $this->membersRepository->create($this->userDTO->member);

            $this->membersRepository->saveMembers(
                $member->id,
                [$this->userDTO->member->churchId]
            );

            $this->usersRepository->saveModules(
                $this->userDTO->id,
                $this->userDTO->modulesId
            );

            Transaction::commit();

            return new InsertMemberResponse(
                $user->id,
                $user->name,
                $user->email,
                $this->profile->id,
                $this->profile->description,
                $this->church->name,
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
