<?php

namespace App\Features\Auth\Contracts;

use App\Features\Auth\DTO\ForgotPasswordDTO;

interface ForgotPasswordBusinessInterface
{
    public function sendEmailForgotPassword(ForgotPasswordDTO $forgotPasswordDTO);
    public function resetPassword(ForgotPasswordDTO $forgotPasswordDTO);
}
