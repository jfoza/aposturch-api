<?php

namespace App\Features\Auth\Http\Responses\Admin;

class AdminUserResponse
{
    public string|null $id;
    public string|null $email;
    public string|null $avatar;
    public string|null $fullName;
    public object|null $role;
    public bool|null   $status;
    public array|null  $ability;
}
