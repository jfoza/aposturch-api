<?php

namespace App\Features\Users\NewPasswordGenerations\DTO;

class NewPasswordGenerationsDTO
{
    public string $userId;
    public string $email;
    public string $password;
    public string $passwordEncrypt;
}
