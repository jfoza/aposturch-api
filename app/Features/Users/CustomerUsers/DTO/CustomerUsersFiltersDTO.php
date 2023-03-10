<?php

namespace App\Features\Users\CustomerUsers\DTO;

use App\Features\Base\DTO\FiltersDTO;

class CustomerUsersFiltersDTO extends FiltersDTO
{
    public string|null $userId;
    public string|null $name;
    public string|null $email;
    public string|null $city;
    public bool|null   $active;
}
