<?php

namespace Tests\Unit\App\Resources;

use App\Features\General\Images\Enums\TypeUploadImageEnum;
use App\Features\General\Images\Models\Image;
use Ramsey\Uuid\Uuid;

class ImagesLists
{
    public static function getImageCreated(?string $imageId = null): object
    {
        if(is_null($imageId))
        {
            $imageId = Uuid::uuid4()->toString();
        }

        return (object) ([
            Image::ID => $imageId,
            Image::TYPE => TypeUploadImageEnum::CHURCH->value,
            Image::PATH => 'example/example.png',
        ]);
    }
}
