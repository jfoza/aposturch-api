<?php

namespace App\Features\Users\ForgotPassword\Contracts;

use App\Features\Users\ForgotPassword\DTO\ForgotPasswordDTO;

interface ForgotPasswordRepositoryInterface
{
    public function findAll();
    public function findById(string $id);
    public function findAllByUserId(string $userId);
    public function findByCode(string $code);
    public function saveForgotPassword(ForgotPasswordDTO $forgotPasswordDTO);
    public function invalidateForgotPassword(string $forgotPasswordId, string $validate);
}
