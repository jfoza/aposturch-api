<?php

namespace App\Base\Traits;

use App\Features\Users\Sessions\Contracts\SessionsRepositoryInterface;

trait AutomaticLogoutTrait
{
    protected SessionsRepositoryInterface $sessionsRepository;

    public function invalidateSessionsUser(string $userId): void
    {
        $this->sessionsRepository = app(SessionsRepositoryInterface::class);

        $this->sessionsRepository->inactivateAll($userId);
    }
}
