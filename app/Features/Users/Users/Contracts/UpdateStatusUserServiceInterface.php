<?php

namespace App\Features\Users\Users\Contracts;

interface UpdateStatusUserServiceInterface
{
    public function execute(string $userId): array;
}
