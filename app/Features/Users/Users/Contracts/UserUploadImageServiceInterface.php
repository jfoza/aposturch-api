<?php

namespace App\Features\Users\Users\Contracts;

use App\Features\General\Images\DTO\ImagesDTO;

interface UserUploadImageServiceInterface
{
    public function execute(ImagesDTO $imagesDTO, string $userId);
}
