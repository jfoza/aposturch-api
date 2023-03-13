<?php

namespace App\Features\Users\AdminUsers\Http\Responses;

class LoggedUserResponse
{
    public ?string $id;
    public ?string $email;
    public ?string $avatar;
    public ?string $fullName;
    public mixed $role;
    public ?string $status;
    public ?array $ability;
}
