<?php

namespace App\Features\Users\Profiles\Contracts;

use App\Features\Users\Profiles\DTO\ProfilesFiltersDTO;

interface ProfilesRepositoryInterface
{
    public function findAll(ProfilesFiltersDTO $profilesFiltersDTO);
    public function findOneByUniqueName(string $uniqueName);
    public function findById(string $id);
    public function findByIds(array $ids);
    public function findByIdAndUniqueName(string $id, array $uniqueName);
    public function findCountUsersByProfile(string $profile);
}
