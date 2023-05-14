<?php

namespace App\Modules\Membership\Members\Types;

use App\Features\Persons\Contracts\PersonsRepositoryInterface;
use App\Features\Users\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Modules\Membership\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;

readonly class OperationsType
{
    public function __construct(
        public PersonsRepositoryInterface  $personsRepository,
        public UsersRepositoryInterface    $usersRepository,
        public ProfilesRepositoryInterface $profilesRepository,
        public MembersRepositoryInterface  $membersRepository,
        public ChurchRepositoryInterface   $churchRepository,
    ) {}
}
