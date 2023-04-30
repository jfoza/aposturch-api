<?php

namespace App\Modules\Membership\Church\Contracts;

use App\Features\General\Images\DTO\ImagesDTO;

interface ChurchUploadImageServiceInterface
{
    public function execute(ImagesDTO $imagesDTO, string $churchId);
}
