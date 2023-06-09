<?php

namespace Tests\Feature\Resources\Auth;

use Ramsey\Uuid\Uuid;

trait AuthCredentials
{
    private string $email;
    private string $password;

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function defineAdminUserTypeCredentials(): void
    {
        $this->setEmail('gfozza@hotmail.com');
        $this->setPassword('Teste123');
    }

    public function defineAdminUserTypeInactiveCredentials(): void
    {
        $this->setEmail('inactive-user@hotmail.com');
        $this->setPassword('Teste123');
    }

    public function getInvalidAuthorizationBearer(): array
    {
        $token = hash('sha256', Uuid::uuid4()->toString());

        return ['Authorization' => "Bearer {$token}"];
    }
}
