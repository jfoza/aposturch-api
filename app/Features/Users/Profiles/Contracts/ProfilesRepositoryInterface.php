<?php

namespace App\Features\Users\Profiles\Contracts;

interface ProfilesRepositoryInterface
{
    public function findAll();
    public function findAllByUniqueName(array $uniqueNames);
    public function findOneByUniqueName(string $uniqueName);
    public function findById(string $id);
    public function findByIds(array $ids);
    public function findByIdAndUniqueName(string $id, array $uniqueName);
}
