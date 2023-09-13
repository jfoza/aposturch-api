<?php

namespace App\Features\Users\Users\DTO;

class NewPasswordDTO
{
    public ?string $userId;
    public ?string $currentPassword;
    public ?string $newPassword;
}
