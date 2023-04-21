<?php

namespace App\Features\Auth\Controllers;

use App\Features\Auth\Contracts\ForgotPasswordBusinessInterface;
use App\Features\Auth\DTO\ForgotPasswordDTO;
use App\Features\Auth\Requests\EmailRequest;
use App\Features\Auth\Requests\ResetPasswordRequest;
use App\Shared\Enums\MessagesEnum;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

readonly class ForgotPasswordController
{
    public function __construct(
        private ForgotPasswordBusinessInterface $forgotPasswordBusiness,
    ) {}

    public function sendEmail(
        EmailRequest $request,
        ForgotPasswordDTO $forgotPasswordDTO
    ): JsonResponse
    {
        $forgotPasswordDTO->email = $request->email;

        $this->forgotPasswordBusiness->sendEmailForgotPassword($forgotPasswordDTO);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    public function resetPassword(
        ResetPasswordRequest $request,
        ForgotPasswordDTO $forgotPasswordDTO
    ): JsonResponse
    {
        $forgotPasswordDTO->code = $request->code;
        $forgotPasswordDTO->newPassword = $request->password;

        $this->forgotPasswordBusiness->resetPassword($forgotPasswordDTO);

        return response()->json(MessagesEnum::SUCCESS_MODIFY_PASSWORD, Response::HTTP_OK);
    }
}
