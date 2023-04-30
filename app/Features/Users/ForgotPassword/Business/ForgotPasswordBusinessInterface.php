<?php

namespace App\Features\Users\ForgotPassword\Business;

use App\Features\Users\ForgotPassword\DTO\ForgotPasswordDTO;

interface ForgotPasswordBusinessInterface
{
    public function sendEmailForgotPassword(ForgotPasswordDTO $forgotPasswordDTO);
    public function resetPassword(ForgotPasswordDTO $forgotPasswordDTO);
}
