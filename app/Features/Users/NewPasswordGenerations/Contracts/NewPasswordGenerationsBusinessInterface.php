<?php

namespace App\Features\Users\NewPasswordGenerations\Contracts;

use App\Features\Users\NewPasswordGenerations\DTO\NewPasswordGenerationsDTO;
use App\Features\Users\NewPasswordGenerations\Http\Responses\NewPasswordGenerationsResponse;

interface NewPasswordGenerationsBusinessInterface
{
    public function save(NewPasswordGenerationsDTO $newPasswordGenerationsDTO): NewPasswordGenerationsResponse;
}
