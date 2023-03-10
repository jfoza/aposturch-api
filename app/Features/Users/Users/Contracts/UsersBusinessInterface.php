<?php

namespace App\Features\Users\Users\Contracts;

interface UsersBusinessInterface
{
    public function findById(string $id);
}
