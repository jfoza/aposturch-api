<?php

namespace App\Features\Users\NewPasswordGenerations\Contracts;

interface NewPasswordGenerationsRepositoryInterface
{
    public function create(string $userId);
}
