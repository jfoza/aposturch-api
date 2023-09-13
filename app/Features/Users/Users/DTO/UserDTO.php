<?php

namespace App\Features\Users\Users\DTO;

use App\Features\Persons\DTO\PersonDTO;
use App\Features\Users\EmailVerification\DTO\EmailVerificationDTO;
use App\Modules\Membership\Members\DTO\MemberDTO;

class UserDTO
{
    public string|null $id;
    public string|null $personId;
    public string|null $name;
    public string|null $email;
    public string|null $profileId;
    public array|null  $modulesId;
    public bool|null   $active;

    public function __construct(
        public PersonDTO            $person,
        public MemberDTO            $member,
        public EmailVerificationDTO $emailVerificationDTO,
        public PasswordDTO          $passwordDTO,
    ) {}
}
