<?php

namespace App\Features\Users\ForgotPassword\Infra\Repositories;

use App\Features\Auth\Contracts\ForgotPasswordRepositoryInterface;
use App\Features\Auth\DTO\ForgotPasswordDTO;
use App\Features\Users\ForgotPassword\Infra\Models\ForgotPassword;

class ForgotPasswordRepository implements ForgotPasswordRepositoryInterface
{
    public function findAll()
    {
        return ForgotPassword::paginate();
    }

    public function findAllByUserId(string $userId)
    {
        return ForgotPassword::where(ForgotPassword::USER_ID, $userId)->get();
    }

    public function findById(string $id)
    {
        return ForgotPassword::where(ForgotPassword::ID, $id)->first();
    }

    public function findByCode(string $code) {
        return ForgotPassword::with('user')
            ->where(ForgotPassword::CODE, $code)
            ->first();
    }

    public function saveForgotPassword(ForgotPasswordDTO $forgotPasswordDTO) {
        $forgotPasswordData = [
            ForgotPassword::USER_ID  => $forgotPasswordDTO->userId,
            ForgotPassword::CODE     => $forgotPasswordDTO->code,
            ForgotPassword::VALIDATE => $forgotPasswordDTO->validate,
            ForgotPassword::ACTIVE   => $forgotPasswordDTO->active
        ];

        ForgotPassword::insert($forgotPasswordData);

        return $forgotPasswordData;
    }

    public function invalidateForgotPassword(string $forgotPasswordId, string $validate)
    {
        ForgotPassword::where(ForgotPassword::ID, $forgotPasswordId)
            ->update([
                ForgotPassword::ACTIVE => false,
                ForgotPassword::VALIDATE => $validate
            ]);
    }
}


