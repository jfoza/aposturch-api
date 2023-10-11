<?php

namespace App\Features\Users\AdminUsers\DTO;

use App\Base\DTO\FiltersDTO;

class AdminUsersFiltersDTO extends FiltersDTO
{
    public string|null $userId;
    public string|null $name;
    public string|null $email;
    public array|null $profileUniqueName;
    public bool $resource;
}
