<?php

namespace App\Features\Users\Users\DTO;

use App\Features\Persons\DTO\PersonDTO;
use App\Features\Users\CustomerUsers\DTO\CustomerUsersDTO;
use App\Features\Users\EmailVerification\DTO\EmailVerificationDTO;
use App\Features\Users\NewPasswordGenerations\DTO\NewPasswordGenerationsDTO;

class UserDTO
{
    public string|null $id;
    public string|null $personId;
    public string|null $name;
    public string|null $email;
    public string|null $password;
    public string|null $profileId;
    public bool|null   $active;

    public function __construct(
        public PersonDTO $person,
        public CustomerUsersDTO $customerUsersDTO,
        public EmailVerificationDTO $emailVerificationDTO,
        public NewPasswordGenerationsDTO $newPasswordGenerationsDTO,
    ) {}
}
