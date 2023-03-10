<?php

namespace App\Features\Users\NewPasswordGenerations\Http\Resources;

use App\Features\Users\NewPasswordGenerations\Http\Responses\NewPasswordGenerationsResponse;

class NewPasswordGenerationsResource
{
    public function __construct(
        private readonly NewPasswordGenerationsResponse $newPasswordGenerationsResponse,
    ) {}

    /**
     * @return NewPasswordGenerationsResponse
     */
    public function getNewPasswordGenerationsResponse(): NewPasswordGenerationsResponse
    {
        return $this->newPasswordGenerationsResponse;
    }

    /**
     * @param object $newPasswordGeneration
     * @param string $email
     */
    public function setNewPasswordGenerationsResponse(object $newPasswordGeneration, string $email): void
    {
        $this->newPasswordGenerationsResponse->id        = $newPasswordGeneration->id;
        $this->newPasswordGenerationsResponse->userId    = $newPasswordGeneration->user_id;
        $this->newPasswordGenerationsResponse->createdAt = $newPasswordGeneration->created_at;
        $this->newPasswordGenerationsResponse->email     = $email;
    }
}
