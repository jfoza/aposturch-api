<?php

namespace App\Features\Users\Sessions\Services;

use App\Features\Auth\DTO\AuthDTO;
use App\Features\Users\Sessions\Contracts\CreateSessionDataServiceInterface;
use App\Features\Users\Sessions\Contracts\SessionsRepositoryInterface;
use App\Features\Users\Sessions\Models\Session;

readonly class CreateSessionDataService implements CreateSessionDataServiceInterface
{
    public function __construct(
        private SessionsRepositoryInterface $sessionsRepository
    ) {}

    public function execute(AuthDTO $authDTO): Session
    {
        return $this->sessionsRepository->create($authDTO);
    }
}
