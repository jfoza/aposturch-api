<?php

namespace App\Features\Users\Sessions\Repositories;

use App\Features\Auth\DTO\AuthDTO;
use App\Features\Users\Sessions\Contracts\SessionsRepositoryInterface;
use App\Features\Users\Sessions\DTO\SessionDTO;
use App\Features\Users\Sessions\Models\Session;

class SessionsRepository implements SessionsRepositoryInterface
{
    public function findByUserId(array $userId)
    {
        return Session::with('user')
            ->whereIn(Session::USER_ID, $userId)
            ->get();
    }

    public function findByToken(string $token)
    {
        return Session::with('user')
            ->where(Session::TOKEN, $token)
            ->get();
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
