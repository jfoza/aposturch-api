<?php

namespace App\Features\Users\Sessions\Repositories;

use App\Features\Auth\DTO\AuthDTO;
use App\Features\Users\Sessions\Contracts\SessionsRepositoryInterface;
use App\Features\Users\Sessions\DTO\SessionDTO;
use App\Features\Users\Sessions\Models\Session;
use Illuminate\Database\Eloquent\Collection;

class SessionsRepository implements SessionsRepositoryInterface
{
    public function findByUserId(string $userId): Collection|array
    {
        return Session::with('user')
            ->where(Session::USER_ID, $userId)
            ->get();
    }

    public function findByUserIdAndDates(
        string $userId,
        string $initialDate,
        string $finalDate
    ): Collection|array
    {
        return Session::with('user')
            ->where(Session::USER_ID, $userId)
            ->whereBetween(Session::INITIAL_DATE, [$initialDate, $finalDate])
            ->get();
    }

    public function inactivateAll(string $userId): void
    {
        Session::where(Session::USER_ID, $userId)->update([Session::ACTIVE => false]);
    }

    public function create(SessionDTO $sessionDTO)
    {
        return Session::create([
            Session::USER_ID      => $sessionDTO->userId,
            Session::INITIAL_DATE => $sessionDTO->initialDate,
            Session::FINAL_DATE   => $sessionDTO->finalDate,
            Session::TOKEN        => $sessionDTO->token,
            Session::IP_ADDRESS   => $sessionDTO->ipAddress,
        ]);
    }
}
