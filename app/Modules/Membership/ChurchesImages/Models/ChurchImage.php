<?php

namespace App\Modules\Membership\ChurchesImages\Models;

use App\Base\Infra\Models\Register;

class ChurchImage extends Register
{
    const ID = 'id';
    const CHURCH_ID = 'church_id';
    const IMAGE_ID = 'image_id';

    protected $table = 'membership.churches_images';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';
}
