<?php

namespace Tests\Feature\Resources\Auth;

use Ramsey\Uuid\Uuid;

trait AuthCredentials
{
    private string $email;
    private string $password;

    public string $inactiveUserEmail = 'inactive-user@hotmail.com';
    public string $testUserEmail = 'usuario-para-testes@hotmail.com';
    public string $defaultPass = 'Teste123';
    public string $adminMasterUserEmail = 'gfozza@hotmail.com';
    public string $adminChurchUserEmail = 'felipe-dutra@hotmail.com';
    public string $adminModuleUserEmail = 'fabio-dutra@hotmail.com';

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
        $this->setEmail($this->adminMasterUserEmail);
        $this->setPassword($this->defaultPass);
    }

    public function defineAdminUserTypeInactiveCredentials(): void
    {
        $this->setEmail($this->inactiveUserEmail);
        $this->setPassword($this->defaultPass);
    }

    public function getInvalidAuthorizationBearer(): array
    {
        $token = hash('sha256', Uuid::uuid4()->toString());

        return ['Authorization' => "Bearer {$token}"];
    }
}
