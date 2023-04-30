<?php

namespace App\Features\Auth\Business;

use App\Exceptions\AppException;
use App\Features\Auth\Validations\AuthValidations;
use App\Features\Users\CustomerUsers\Contracts\CustomerUsersRepositoryInterface;
use App\Features\Users\ForgotPassword\Business\ForgotPasswordBusinessInterface;
use App\Features\Users\ForgotPassword\Contracts\ForgotPasswordRepositoryInterface;
use App\Features\Users\ForgotPassword\DTO\ForgotPasswordDTO;
use App\Features\Users\ForgotPassword\Jobs\SendEmailForgotPasswordJob;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Shared\Helpers\Helpers;
use App\Shared\Helpers\RandomStringHelper;
use App\Shared\Utils\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ForgotPasswordBusiness implements ForgotPasswordBusinessInterface
{
    private Carbon $currentDate;

    public function __construct(
        private readonly ForgotPasswordRepositoryInterface $forgotPasswordRepository,
        private readonly UsersRepositoryInterface $usersRepository,
        private readonly CustomerUsersRepositoryInterface $customerUsersRepository,
    ) {
        $this->currentDate = Helpers::getCurrentTimestampCarbon();
    }

    /**
     * @throws AppException
     */
    public function sendEmailForgotPassword(ForgotPasswordDTO $forgotPasswordDTO): void
    {
        $customerUser = $this->customerUsersRepository->findByUserEmail($forgotPasswordDTO->email);
        $user         = AuthValidations::userExistsForgotPassword($customerUser);

        AuthValidations::validateIfUserHasAlreadyVerifiedEmail($customerUser->verified_email, false);

        $forgotPasswordDTO->userId   = $user->id;
        $forgotPasswordDTO->code     = RandomStringHelper::uuidv4Generate();
        $forgotPasswordDTO->validate = $this->currentDate->addHours(1)->format('Y-m-d H:i:s');
        $forgotPasswordDTO->active   = true;

        $this->forgotPasswordRepository->saveForgotPassword($forgotPasswordDTO);

        SendEmailForgotPasswordJob::dispatch(
            $forgotPasswordDTO->email,
            $forgotPasswordDTO->code
        );
    }

    /**
     * @throws AppException
     */
    public function resetPassword(ForgotPasswordDTO $forgotPasswordDTO)
    {
        $forgotPassword = $this->forgotPasswordRepository->findByCode($forgotPasswordDTO->code);

        AuthValidations::forgotPasswordExists($forgotPassword);

        AuthValidations::isValidForgotPassword(
            $this->currentDate,
            $forgotPassword->validate,
            $forgotPassword->active
        );

        $forgotPasswordDTO->userId = $forgotPassword->user->id;
        $forgotPasswordDTO->newPassword = Hash::generateHash($forgotPasswordDTO->newPassword);

        DB::beginTransaction();

        try {
            $this->usersRepository->saveNewPassword(
                $forgotPasswordDTO->userId,
                $forgotPasswordDTO->newPassword
            );

            $this->forgotPasswordRepository->invalidateForgotPassword(
                $forgotPassword->id,
                $this->currentDate->subDays(2)->format('Y-m-d H:i:s')
            );

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            throw new AppException($e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
