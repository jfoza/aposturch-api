<?php

namespace App\Features\Users\NewPasswordGenerations\Business;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\Base\Traits\EnvironmentException;
use App\Features\Users\CustomerUsers\Contracts\CustomerUsersRepositoryInterface;
use App\Features\Users\CustomerUsers\Jobs\EmailGeneratedCustomerUserJob;
use App\Features\Users\CustomerUsers\Services\Utils\CustomerUserPasswordGeneratorService;
use App\Features\Users\NewPasswordGenerations\Contracts\NewPasswordGenerationsBusinessInterface;
use App\Features\Users\NewPasswordGenerations\Contracts\NewPasswordGenerationsRepositoryInterface;
use App\Features\Users\NewPasswordGenerations\DTO\NewPasswordGenerationsDTO;
use App\Features\Users\NewPasswordGenerations\Http\Resources\NewPasswordGenerationsResource;
use App\Features\Users\NewPasswordGenerations\Http\Responses\NewPasswordGenerationsResponse;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;
use Symfony\Component\HttpFoundation\Response;

class NewPasswordGenerationsBusiness extends Business implements NewPasswordGenerationsBusinessInterface
{
    public function __construct(
        private readonly UsersRepositoryInterface $usersRepository,
        private readonly CustomerUsersRepositoryInterface $customerUsersRepository,
        private readonly NewPasswordGenerationsRepositoryInterface $newPasswordGenerationsRepository,
        private readonly NewPasswordGenerationsResource $newPasswordGenerationsResource,
    ) {}

    /**
     * @throws AppException
     */
    public function save(NewPasswordGenerationsDTO $newPasswordGenerationsDTO): NewPasswordGenerationsResponse
    {
        $this->getPolicy()->havePermission(RulesEnum::CUSTOMERS_UPDATE->value);

        $customerUser = $this->customerUsersRepository->findByUserId($newPasswordGenerationsDTO->userId);

        if(empty($customerUser)) {
            throw new AppException(
                MessagesEnum::USER_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        Transaction::beginTransaction();

        try {
            $passwordGenerate = CustomerUserPasswordGeneratorService::execute();

            $newPasswordGenerationsDTO->email           = $customerUser->user->email;
            $newPasswordGenerationsDTO->password        = $passwordGenerate->password;
            $newPasswordGenerationsDTO->passwordEncrypt = $passwordGenerate->passwordEncrypt;

            $this
                ->usersRepository
                ->saveNewPassword(
                    $newPasswordGenerationsDTO->userId,
                    $newPasswordGenerationsDTO->passwordEncrypt
                );

            $newPasswordGeneration = $this
                ->newPasswordGenerationsRepository
                ->create($newPasswordGenerationsDTO->userId);

            Transaction::commit();

            EmailGeneratedCustomerUserJob::dispatch($newPasswordGenerationsDTO);

            $this
                ->newPasswordGenerationsResource
                ->setNewPasswordGenerationsResponse(
                    $newPasswordGeneration,
                    $newPasswordGenerationsDTO->email
                );

            return $this->newPasswordGenerationsResource->getNewPasswordGenerationsResponse();

        } catch(\Exception $e) {
            Transaction::rollback();

            EnvironmentException::dispatchException($e);
        }
    }
}
