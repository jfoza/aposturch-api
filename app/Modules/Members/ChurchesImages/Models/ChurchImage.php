<?php

namespace App\Modules\Members\ChurchesImages\Models;

use App\Features\Base\Infra\Models\Register;

class ChurchImage extends Register
{
    const ID = 'id';
    const CHURCH_ID = 'church_id';
    const IMAGE_ID = 'image_id';

    protected $table = 'members.churches_images';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';
}