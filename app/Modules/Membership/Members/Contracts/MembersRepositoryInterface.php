<?php

namespace App\Modules\Membership\Members\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface MembersRepositoryInterface
{
    public function findAll();
    public function findAllResponsible();
    public function findById(string $id);
    public function findByIds(array $ids): mixed;
    public function create();
    public function save();
    public function remove(string $id);
}
