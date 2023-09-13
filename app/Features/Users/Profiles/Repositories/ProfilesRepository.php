<?php

namespace App\Features\Users\Profiles\Repositories;

use App\Features\Users\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\Users\Profiles\DTO\ProfilesFiltersDTO;
use App\Features\Users\Profiles\Models\Profile;
use App\Features\Users\Profiles\Models\ProfileType;
use App\Features\Users\ProfilesUsers\Infra\Models\ProfileUser;

class ProfilesRepository implements ProfilesRepositoryInterface
{
    public function findAll(ProfilesFiltersDTO $profilesFiltersDTO)
    {
        return Profile::select(
                Profile::ID,
                Profile::PROFILE_TYPE_ID,
                Profile::DESCRIPTION,
                Profile::UNIQUE_NAME,
                Profile::ACTIVE,
            )
            ->when(
                isset($profilesFiltersDTO->profileTypeId),
                fn($q) => $q->where(Profile::PROFILE_TYPE_ID, $profilesFiltersDTO->profileTypeId)
            )
            ->when(
                isset($profilesFiltersDTO->profileTypeUniqueName),
                fn($q) => $q->whereRelation(
                    'profileType',
                    ProfileType::UNIQUE_NAME,
                    $profilesFiltersDTO->profileTypeUniqueName
                )
            )
            ->when(
                isset($profilesFiltersDTO->profilesUniqueName),
                fn($q) => $q->whereIn(Profile::UNIQUE_NAME, $profilesFiltersDTO->profilesUniqueName)
            )
            ->get();

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

    public function findCountUsersByProfile(string $profile)
    {
        return ProfileUser::where(
                Profile::tableField(Profile::UNIQUE_NAME),
                $profile
            )
            ->leftJoin(
                Profile::tableName(),
                Profile::tableField(Profile::ID),
                ProfileUser::tableField(ProfileUser::PROFILE_ID)
            )
            ->count();
    }
}
