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
use App\Features\Users\CustomerUsers\Services\Utils\CustomerUsersValidationsService;
use App\Features\Users\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\DTO\UserDTO;
use App\Features\Users\Users\Validations\UsersValidations;
use App\Shared\Cache\PolicyCache;

class UpdateCustomerService
{
    use DispatchExceptionTrait;

    public function __construct(
        private readonly PersonsRepositoryInterface       $personsRepository,
        private readonly UsersRepositoryInterface         $usersRepository,
        private readonly ProfilesRepositoryInterface      $profilesRepository,
        private readonly CustomerUsersRepositoryInterface $customerUsersRepository,
        private readonly CityRepositoryInterface          $cityRepository,
        private readonly StateRepositoryInterface         $stateRepository,
        private readonly CustomerUserResource             $customerUserResource
    ) {}

    /**
     * @throws AppException
     */
    public function execute(UserDTO $userDTO, bool $public = false): CustomerUserResponse
    {
        $personUser = CustomerUsersValidationsService::customerUserIdExists(
            $this->customerUsersRepository,
            $userDTO->id
        );

        $user = UsersValidations::emailAlreadyExistsUpdate(
            $this->usersRepository,
            $userDTO->id,
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

        if($public)
        {
            UsersValidations::isActiveUser($user->active);
            $userDTO->active = $user->active;
        }

        $userDTO->person->id = $personUser->user->person->id;
        $person = $this->personsRepository->save($userDTO->person);
        $user = $this->usersRepository->save($userDTO);

        PolicyCache::invalidatePolicy($userDTO->id);

        $this->customerUserResource
            ->setCustomerUserResponse(
                $person,
                $user,
            );

        return $this->customerUserResource->getCustomerUserResponse();
    }
}
