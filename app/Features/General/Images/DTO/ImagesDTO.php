<?php

namespace App\Features\General\Images\DTO;

use Illuminate\Http\UploadedFile;

class ImagesDTO
{
    public ?string $id;
    public ?string $path;
    public ?string $type;
    public UploadedFile $image;
}
