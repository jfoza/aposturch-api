<?php

namespace App\Features\Users\Sessions\Contracts;

use App\Features\Users\Sessions\DTO\SessionDTO;
use Illuminate\Database\Eloquent\Collection;

interface SessionsRepositoryInterface
{
    public function findByUserId(string $userId): Collection|array;
    public function findByUserIdAndDates(string $userId, string $initialDate, string $finalDate): Collection|array;
    public function inactivateAll(string $userId): void;
    public function create(SessionDTO $sessionDTO);
}
