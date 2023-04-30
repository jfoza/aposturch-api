<?php

namespace App\Features\Users\Sessions\Contracts;

use App\Features\Users\Sessions\DTO\SessionDTO;

interface SessionsRepositoryInterface
{
    public function findByUserId(array $userId);
    public function findByToken(string $token);
    public function create(SessionDTO $sessionDTO);
}
