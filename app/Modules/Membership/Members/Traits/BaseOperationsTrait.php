<?php

namespace App\Modules\Membership\Members\Traits;

use App\Exceptions\AppException;
use App\Features\Base\Traits\EnvironmentException;
use App\Features\Users\Users\DTO\UserDTO;
use App\Modules\Membership\Members\Responses\InsertMemberResponse;
use App\Modules\Membership\Members\Responses\UpdateMemberResponse;
use App\Modules\Membership\Members\Types\OperationsType;
use App\Shared\Cache\PolicyCache;
use App\Shared\Utils\Hash;
use App\Shared\Utils\Transaction;
use Exception;

trait BaseOperationsTrait
{
    /**
     * @param UserDTO $userDTO
     * @param OperationsType $operationsType
     * @return InsertMemberResponse
     * @throws AppException
     */
    public function createNewMember(
        UserDTO $userDTO,
        OperationsType $operationsType
    ): InsertMemberResponse
    {
        Transaction::beginTransaction();

        try
        {
            $this->userDTO->newPasswordGenerationsDTO->passwordEncrypt = Hash::generateHash($this->userDTO->password);

            $person = $operationsType->personsRepository->create($userDTO->person);
            $userDTO->personId = $person->id;

            $user = $operationsType->usersRepository->create($userDTO, true);
            $userDTO->id = $user->id;

            $operationsType->usersRepository->saveProfiles($userDTO->id, [$userDTO->profileId]);

            $userDTO->member->userId = $user->id;

            $member = $operationsType->membersRepository->create($userDTO->member);

            $operationsType->churchRepository->saveMembers(
                $userDTO->member->churchId,
                [$member->id]
            );

            Transaction::commit();

            return new InsertMemberResponse(
                $user->id,
                $user->name,
                $user->email,
                $user->active,
                $userDTO->profile->id,
                $userDTO->profile->description,
                $userDTO->church->name,
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

    /**
     * @param UserDTO $userDTO
     * @param OperationsType $operationsType
     * @return UpdateMemberResponse
     * @throws AppException
     */
    public function updateMemberData(
        UserDTO $userDTO,
        OperationsType $operationsType
    ): UpdateMemberResponse
    {
        Transaction::beginTransaction();

        try
        {
            $userDTO->person->id = $userDTO->memberUser->person_id;

            $person = $operationsType->personsRepository->save($userDTO->person);
            $user = $operationsType->usersRepository->saveInMembers($userDTO);

            PolicyCache::invalidatePolicy($userDTO->id);

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
