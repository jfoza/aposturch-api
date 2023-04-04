<?php

namespace App\Features\Users\AdminUsers\DTO;

use App\Features\Base\DTO\FiltersDTO;

class AdminUsersFiltersDTO extends FiltersDTO
{
    public string|null $userId;
    public string|null $name;
    public string|null $profileId;
    public array|null $profileUniqueName;
    public string|null $email;
    public bool $resource;
}
