<?php

namespace App\Features\General\Images\Enums;

enum TypeUploadImageEnum: string
{
    case PRODUCT = 'product';
    case CHURCH = 'church';
    case USER_AVATAR = 'user-avatar';
}
