<?php

namespace App\Modules\Members\Church\Contracts;

use App\Features\General\Images\DTO\ImagesDTO;

interface ChurchUploadImageServiceInterface
{
    public function execute(ImagesDTO $imagesDTO, string $churchId);
}
