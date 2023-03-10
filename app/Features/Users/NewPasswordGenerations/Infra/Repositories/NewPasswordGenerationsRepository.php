<?php

namespace App\Features\Users\NewPasswordGenerations\Infra\Repositories;

use App\Features\Users\NewPasswordGenerations\Contracts\NewPasswordGenerationsRepositoryInterface;
use App\Features\Users\NewPasswordGenerations\Infra\Models\NewPasswordGenerations;

class NewPasswordGenerationsRepository implements NewPasswordGenerationsRepositoryInterface
{
    public function create(string $userId)
    {
        return NewPasswordGenerations::create([
            NewPasswordGenerations::USER_ID => $userId
        ]);
    }
}
