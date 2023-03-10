<?php

namespace App\Features\Users\CustomerUsers\Services;

use App\Exceptions\AppException;
use App\Features\Base\Traits\DispatchExceptionTrait;
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
use App\Features\Users\Users\Services\Utils\HashService;
use App\Features\Users\Users\Services\Utils\UsersValidationsService;
use App\Shared\Utils\Transaction;

;

class CreateCustomerService
{
    use DispatchExceptionTrait;

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
        UsersValidationsService::emailAlreadyExists(
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
            $userDTO->newPasswordGenerationsDTO->passwordEncrypt = HashService::generateHash($userDTO->password);

            $person = $this->personsRepository->create($userDTO->person);
            $userDTO->personId = $person->id;

            $user = $this->usersRepository->create($userDTO, true);
            $userDTO->id = $user->id;
            $userDTO->customerUsersDTO->userId = $user->id;

            $this->customerUsersRepository->create($userDTO->customerUsersDTO);

            $userDTO->profileId = $this
                ->profilesRepository
                ->findOneByUniqueName(ProfileUniqueNameEnum::CUSTOMER)
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

            $this->dispatchException($e);
        }
    }
}
