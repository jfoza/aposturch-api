<?php

namespace App\Features\Users\Sessions\Contracts;

use App\Features\Auth\DTO\SessionsDTO;

interface SessionsRepositoryInterface
{
    public function findByUserId(array $userId);
    public function findByToken(string $token);
    public function create(SessionsDTO $sessionsDTO);
}
