<?php

namespace App\Modules\Membership\Church\Utils;

use App\Modules\Membership\Church\Models\Church;

class ChurchUtils
{
    public static function extractChurchesId(mixed $member): array
    {
        return $member->church->pluck(Church::ID)->toArray();
    }
}
