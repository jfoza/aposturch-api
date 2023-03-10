<?php

namespace App\Features\Auth\DTO;

class ForgotPasswordDTO
{
    public string|null $email;
    public string|null $newPassword;
    public string|null $userId;
    public string|null $code;
    public string|null $validate;
    public string|null $active;
}
