<?php

namespace App\Features\Users\AdminUsers\Responses;

class LoggedUserResponse
{
    public ?string $id;
    public ?string $email;
    public ?string $avatarId;
    public ?string $fullName;
    public mixed $role;
    public ?string $status;
    public ?array $ability;
}
