<?php

namespace App\Features\Users\EmailVerification\DTO;

class EmailVerificationDTO
{
    public string|null $userId;
    public string|null $email;
    public string|null $code;
    public bool|null $active;
    public string|null $validate;
}
