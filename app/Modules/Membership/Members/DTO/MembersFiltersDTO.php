<?php

namespace App\Modules\Membership\Members\DTO;

use App\Features\Base\DTO\FiltersDTO;

class MembersFiltersDTO extends FiltersDTO
{
    public ?string $name;
    public ?string $phone;
    public ?string $email;
    public ?string $cityId;

    public ?string $profileId;

    public ?array $churchesId;
    public ?array $modulesId;
    public ?array $profilesUniqueName;
}
