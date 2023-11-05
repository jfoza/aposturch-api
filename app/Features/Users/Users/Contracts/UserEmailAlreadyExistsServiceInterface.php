<?php

namespace App\Features\Users\Users\Contracts;

interface UserEmailAlreadyExistsServiceInterface
{
    public function execute(string $email): void;
}
