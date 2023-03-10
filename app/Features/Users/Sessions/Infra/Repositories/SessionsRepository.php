<?php

namespace App\Features\Users\Sessions\Infra\Repositories;

use App\Features\Auth\DTO\SessionsDTO;
use App\Features\Users\Sessions\Contracts\SessionsRepositoryInterface;
use App\Features\Users\Sessions\Infra\Models\Session;

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

    public function create(SessionsDTO $sessionsDTO)
    {
        return Session::create([
            Session::USER_ID      => $sessionsDTO->userId,
            Session::INITIAL_DATE => $sessionsDTO->initialDate,
            Session::FINAL_DATE   => $sessionsDTO->finalDate,
            Session::TOKEN        => $sessionsDTO->token,
            Session::IP_ADDRESS   => $sessionsDTO->ipAddress,
        ]);
    }
}
