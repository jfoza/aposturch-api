<?php

namespace App\Features\Users\Users\DTO;

use App\Base\DTO\FiltersDTO;

class UserFiltersDTO extends FiltersDTO
{
    public ?string $name;
    public ?string $churchId;
}
