<?php

namespace App\Features\Users\CustomerUsers\Business;

use App\Exceptions\AppException;
use App\Features\Users\CustomerUsers\Contracts\PublicCustomerUsersBusinessInterface;
use App\Features\Users\CustomerUsers\Http\Responses\CustomerUserResponse;
use App\Features\Users\CustomerUsers\Services\AuthorizeCustomerUserService;
use App\Features\Users\CustomerUsers\Services\CreateCustomerService;
use App\Features\Users\CustomerUsers\Services\EmailSendingService;
use App\Features\Users\CustomerUsers\Services\ShowCustomerByEmailService;
use App\Features\Users\CustomerUsers\Services\ShowCustomerService;
use App\Features\Users\CustomerUsers\Services\UpdateCustomerService;
use App\Features\Users\CustomerUsers\Services\Utils\CustomerUsersValidationsService;
use App\Features\Users\EmailVerification\Services\InvalidateEmailVerificationCodeService;
use App\Features\Users\Users\DTO\PasswordDTO;
use App\Features\Users\Users\DTO\UserDTO;
use App\Features\Users\Users\Services\UpdateUserPasswordService;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Utils\Auth;
use App\Shared\Utils\Transaction;
use Exception;
use Symfony\Component\HttpFoundation\Response;

readonly class PublicCustomerUsersBusiness implements PublicCustomerUsersBusinessInterface
{
    public function __construct(
        private ShowCustomerService                    $showCustomerService,
        private ShowCustomerByEmailService             $showCustomerByEmailService,
        private CreateCustomerService                  $createCustomerService,
        private UpdateCustomerService                  $updateCustomerService,
        private AuthorizeCustomerUserService           $authorizeCustomerUserService,
        private UpdateUserPasswordService              $updateUserPasswordService,
        private EmailSendingService                    $emailSendingService,
        private InvalidateEmailVerificationCodeService $invalidateEmailVerificationCodeService,
    ) {}

    /**
     * @throws AppException
     */
    public function findById()
    {
        return $this->showCustomerService->execute(Auth::getId());
    }

    /**
     * @throws AppException
     */
    public function verifyEmailExists(string $email)
    {
        if (!empty($this->showCustomerByEmailService->execute($email))) {
            throw new AppException(
                MessagesEnum::EMAIL_ALREADY_EXISTS,
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * @throws AppException
     * @throws Exception
     */
    public function create(UserDTO $userDTO): CustomerUserResponse
    {
        $userDTO->customerUsersDTO->verifiedEmail = false;

        $created = $this->createCustomerService->execute($userDTO, true);

        $this->emailSendingService->execute($created->id, $created->email);

        return $created;
    }

    /**
     * @throws AppException
     * @throws Exception
     */
    public function authorizeCustomerUser(string $userId, string $code)
    {
        Transaction::beginTransaction();

        try
        {
            $this->authorizeCustomerUserService->execute($userId, $code);

            $this->invalidateEmailVerificationCodeService->execute($code);

            Transaction::commit();
        }
        catch (\Exception $e)
        {
            Transaction::rollback();

            throw($e);
        }
    }

    /**
     * @throws AppException
     */
    public function resendEmailNewCustomerUser(string $email)
    {
        if (!$customerUser = $this->showCustomerByEmailService->execute($email))
        {
            throw new AppException(
                MessagesEnum::USER_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        CustomerUsersValidationsService::isEmailAlreadyVerify(
            $customerUser->customer_user_verified_email
        );

        $this->emailSendingService->execute(
            $customerUser->user_id,
            $customerUser->user_email
        );
    }

    /**
     * @throws Exception
     */
    public function save(UserDTO $userDTO): CustomerUserResponse
    {
        Transaction::beginTransaction();

        try
        {
            $userDTO->id = Auth::getId();

            $updated = $this->updateCustomerService->execute($userDTO, true);

            Transaction::commit();
        }
        catch (\Exception $e)
        {
            Transaction::rollback();

            throw($e);
        }

        return $updated;
    }

    /**
     * @throws Exception
     */
    public function saveNewPassword(PasswordDTO $passwordDTO)
    {
        Transaction::beginTransaction();

        try
        {
            $passwordDTO->userId = Auth::getId();

            $this->updateUserPasswordService->execute($passwordDTO);

            Transaction::commit();

            Auth::logout();
        }
        catch (\Exception $e)
        {
            Transaction::rollback();

            throw($e);
        }
    }
}
