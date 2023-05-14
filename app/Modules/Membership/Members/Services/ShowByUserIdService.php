<?php

namespace App\Modules\Membership\Members\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\Service;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\Contracts\ShowByUserIdServiceInterface;
use App\Shared\Enums\MessagesEnum;
use Symfony\Component\HttpFoundation\Response;

class ShowByUserIdService extends Service implements ShowByUserIdServiceInterface
{
    public function __construct(
        private readonly MembersRepositoryInterface $membersRepository
    ) {}

    /**
     * @throws AppException
     */
    public function execute(string $userId): object
    {
        if(!$member = $this->membersRepository->findByUserId($userId))
        {
            throw new AppException(
                MessagesEnum::USER_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        return $member;
    }
}
