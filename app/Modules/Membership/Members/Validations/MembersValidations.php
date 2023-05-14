<?php

namespace App\Modules\Membership\Members\Validations;

use App\Exceptions\AppException;
use App\Features\City\Cities\Contracts\CityRepositoryInterface;
use App\Features\City\Cities\Validations\CityValidations;
use App\Features\Users\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\DTO\UserDTO;
use App\Features\Users\Users\Validations\UsersValidations;
use App\Modules\Membership\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Membership\Church\Validations\ChurchValidations;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Shared\Enums\MessagesEnum;
use Symfony\Component\HttpFoundation\Response;

readonly class MembersValidations
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository,
        private MembersRepositoryInterface $membersRepository,
        private ProfilesRepositoryInterface $profilesRepository,
        private ChurchRepositoryInterface $churchRepository,
        private CityRepositoryInterface $cityRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function handleValidationsInCreate(UserDTO $userDTO): UserDTO
    {
        $this->profileIsValid($userDTO);
        $this->emailAlreadyExists($userDTO->email);
        $this->phoneAlreadyExists($userDTO->person->phone);
        $this->churchIsValid($userDTO);
        $this->cityIsValid($userDTO->person->cityId);

        return $userDTO;
    }

    /**
     * @throws AppException
     */
    public function handleValidationsInUpdate(UserDTO $userDTO): UserDTO
    {
        $this->memberExists($userDTO);
        $this->emailAlreadyExistsInUpdate($userDTO);
        $this->phoneAlreadyExistsInUpdate($userDTO);
        $this->cityIsValid($userDTO->person->cityId);

        return $userDTO;
    }

    /**
     * @throws AppException
     */
    public function profileIsValid(UserDTO $userDTO): void
    {
        $profile = UsersValidations::returnProfileExists($this->profilesRepository, $userDTO->profileId);

        $allowedProfiles = [
            ProfileUniqueNameEnum::ADMIN_CHURCH->value,
            ProfileUniqueNameEnum::ADMIN_MODULE->value,
            ProfileUniqueNameEnum::ASSISTANT->value,
            ProfileUniqueNameEnum::MEMBER->value,
        ];

        if(!in_array($profile->unique_name, $allowedProfiles))
        {
            throw new AppException(
                MessagesEnum::INVALID_PROFILE,
                Response::HTTP_BAD_REQUEST
            );
        }

        $userDTO->profile = $profile;
    }

    /**
     * @throws AppException
     */
    public function emailAlreadyExists(string $email): void
    {
        UsersValidations::emailAlreadyExists(
            $this->usersRepository,
            $email
        );
    }

    /**
     * @throws AppException
     */
    public function phoneAlreadyExists(string $phone): void
    {
        UsersValidations::phoneAlreadyExists(
            $this->usersRepository,
            $phone
        );
    }

    /**
     * @throws AppException
     */
    public function churchIsValid(UserDTO $userDTO): void
    {
        $church = ChurchValidations::churchIdExists(
            $this->churchRepository,
            $userDTO->member->churchId
        );

        $userDTO->church = $church;
    }

    /**
     * @throws AppException
     */
    public function cityIsValid(string $cityId): void
    {
        CityValidations::cityIdExists(
            $this->cityRepository,
            $cityId
        );
    }

    /**
     * @throws AppException
     */
    public function memberExists(UserDTO $userDTO): void
    {
        if(!$user = $this->membersRepository->findByUserId($userDTO->id))
        {
            throw new AppException(
                MessagesEnum::USER_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        if(empty($user->person_id))
        {
            throw new AppException(
                MessagesEnum::USER_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        $userDTO->memberUser = $user;
    }

    /**
     * @throws AppException
     */
    public function emailAlreadyExistsInUpdate(UserDTO $userDTO): void
    {
        $user = $this->usersRepository->findByEmail($userDTO->email);

        if($user && $user->id != $userDTO->memberUser->user_id)
        {
            UsersValidations::emailAlreadyExistsUpdateException();
        }
    }

    /**
     * @throws AppException
     */
    public function phoneAlreadyExistsInUpdate(UserDTO $userDTO): void
    {
        $user = $this->usersRepository->findByPhone($userDTO->person->phone);

        if($user && $user->id != $userDTO->memberUser->user_id)
        {
            UsersValidations::phoneAlreadyExistsUpdateException();
        }
    }
}
