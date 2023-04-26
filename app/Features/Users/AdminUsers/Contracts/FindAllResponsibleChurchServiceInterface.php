<?php

namespace App\Features\Users\AdminUsers\Contracts;

interface FindAllResponsibleChurchServiceInterface
{
    public function execute(string $churchId): mixed;
}
