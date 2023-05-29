<?php

namespace App\Features\City\States\Repositories;

use App\Features\City\States\Contracts\StateRepositoryInterface;
use App\Features\City\States\Models\State;

class StateRepository implements StateRepositoryInterface
{
    public function findAll()
    {
        return State::select(
                State::ID,
                State::UF,
                State::DESCRIPTION,
            )
            ->get();
    }

    public function findById(string $id)
    {
        return State::select(
                State::ID,
                State::UF,
                State::DESCRIPTION,
            )
            ->where(State::ID, $id)->first();
    }

    public function findByUF(string $uf)
    {
        return State::select(
                State::ID,
                State::UF,
                State::DESCRIPTION,
            )
            ->where(State::UF, $uf)->first();
    }
}
