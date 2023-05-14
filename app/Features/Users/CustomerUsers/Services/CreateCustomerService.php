<?php

namespace App\Features\Users\CustomerUsers\Services;

use App\Exceptions\AppException;
use App\Features\Base\Traits\EnvironmentException;
use App\Features\City\Cities\Contracts\CityRepositoryInterface;
use App\Features\City\States\Contracts\StateRepositoryInterface;
use App\Features\Persons\Contracts\PersonsRepositoryInterface;
use App\Features\Persons\Services\PersonsValidationsService;
use App\Features\Users\CustomerUsers\Contracts\CustomerUsersRepositoryInterface;
use App\Features\Users\CustomerUsers\Http\Resources\CustomerUserResource;
use App\Features\Users\CustomerUsers\Http\Responses\CustomerUserResponse;
use App\Features\Users\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\DTO\UserDTO;
use App\Features\Users\Users\Validations\UsersValidations;
use App\Shared\Utils\Hash;
use App\Shared\Utils\Transaction;

;

class CreateCustomerService
{
    public function __construct(
        private readonly PersonsRepositoryInterface           $personsRepository,
        private readonly UsersRepositoryInterface             $usersRepository,
        private readonly ProfilesRepositoryInterface          $profilesRepository,
        private readonly CustomerUsersRepositoryInterface     $customerUsersRepository,
        private readonly CityRepositoryInterface              $cityRepository,
        private readonly StateRepositoryInterface             $stateRepository,
        private readonly CustomerUserResource                 $customerUserResource,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(UserDTO $userDTO): CustomerUserResponse
    {
        UsersValidations::emailAlreadyExists(
            $this->usersRepository,
            $userDTO->email
        );

        PersonsValidationsService::validateCityId(
            $this->cityRepository,
            $userDTO->person->cityId
        );

        PersonsValidationsService::validateUF(
            $this->stateRepository,
            $userDTO->person->uf
        );

        Transaction::beginTransaction();

        try
        {
            $userDTO->newPasswordGenerationsDTO->email           = $userDTO->email;
            $userDTO->newPasswordGenerationsDTO->password        = $userDTO->password;
            $userDTO->newPasswordGenerationsDTO->passwordEncrypt = Hash::generateHash($userDTO->password);

            $person = $this->personsRepository->create($userDTO->person);
            $userDTO->personId = $person->id;

            $user = $this->usersRepository->create($userDTO, true);
            $userDTO->id = $user->id;
            $userDTO->customerUsersDTO->userId = $user->id;

            $this->customerUsersRepository->create($userDTO->customerUsersDTO);

            $userDTO->profileId = $this
                ->profilesRepository
                ->findOneByUniqueName('')
                ->id;

            $this->usersRepository->saveProfiles($userDTO->id, [$userDTO->profileId]);

            Transaction::commit();

            $this->customerUserResource
                ->setCustomerUserResponse(
                    $person,
                    $user,
                );

            return $this->customerUserResource->getCustomerUserResponse();

        }
        catch(\Exception $e)
        {
            Transaction::rollback();

            EnvironmentException::dispatchException($e);
        }
    }
}
