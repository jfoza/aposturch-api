<?php

namespace App\Features\Users\Profiles\Infra\Repositories;

use App\Features\Users\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\Users\Profiles\Infra\Models\Profile;

class ProfilesRepository implements ProfilesRepositoryInterface
{
    public function findAll()
    {
        return Profile::get();
    }

    public function findAllByUniqueName(array $uniqueNames)
    {
        return Profile::whereIn(Profile::UNIQUE_NAME, $uniqueNames)->get();
    }

    public function findOneByUniqueName(string $uniqueName)
    {
        return Profile::where(Profile::UNIQUE_NAME, $uniqueName)->first();
    }

    public function findById(string $id)
    {
        return Profile::where(Profile::ID, $id)->first();
    }

    public function findByIds(array $ids)
    {
        return Profile::whereIn(Profile::ID, $ids)->get();
    }

    public function findByIdAndUniqueName(string $id, array $uniqueName)
    {
        return Profile::where(Profile::ID, $id)
            ->whereIn(Profile::UNIQUE_NAME, $uniqueName)
            ->first();
    }
}
