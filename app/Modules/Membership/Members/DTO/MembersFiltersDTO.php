<?php

namespace App\Modules\Membership\Members\DTO;

use App\Features\Base\DTO\FiltersDTO;

class MembersFiltersDTO extends FiltersDTO
{
    public ?array $churchIds;
    public ?string $profileId;
    public ?string $name;
    public ?string $cityId;
}
