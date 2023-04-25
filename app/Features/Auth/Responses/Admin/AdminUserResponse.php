<?php

namespace App\Features\Auth\Responses\Admin;

use Illuminate\Database\Eloquent\Collection;

class AdminUserResponse
{
    public string|null $id;
    public string|null $email;
    public string|null $avatar;
    public string|null $fullName;
    public Collection $role;
    public bool|null   $status;
    public mixed $churches;
    public mixed $responsibleChurch;
    public array|null  $ability;
}
