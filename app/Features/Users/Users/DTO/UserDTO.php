<?php

namespace App\Features\Users\Users\DTO;

use App\Features\Persons\DTO\PersonDTO;
use App\Features\Users\EmailVerification\DTO\EmailVerificationDTO;
use App\Features\Users\NewPasswordGenerations\DTO\NewPasswordGenerationsDTO;
use App\Modules\Membership\Members\DTO\MemberDTO;

class UserDTO
{
    public string|null $id;
    public string|null $personId;
    public string|null $name;
    public string|null $email;
    public string|null $password;
    public string|null $profileId;
    public bool|null   $active;
    public ?object $profile;
    public ?object $church;
    public ?object $memberUser;

    public function __construct(
        public PersonDTO $person,
        public MemberDTO $member,
        public EmailVerificationDTO $emailVerificationDTO,
        public NewPasswordGenerationsDTO $newPasswordGenerationsDTO,
    ) {}
}
