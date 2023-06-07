<?php

namespace App\Features\Base\Services;

use App\Exceptions\AppException;
use App\Shared\Enums\MessagesEnum;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

abstract class AuthenticatedService extends BaseService
{
    private object $authenticatedUser;

    /**
     * @return object
     */
    public function getAuthenticatedUser(): object
    {
        return $this->authenticatedUser;
    }

    /**
     * @param object $authenticatedUser
     */
    public function setAuthenticatedUser(object $authenticatedUser): void
    {
        $this->authenticatedUser = $authenticatedUser;
    }

    public function getAuthenticatedUserId(): string
    {
        return $this->getAuthenticatedUser()->id;
    }

    /**
     * @return Collection
     * @throws AppException
     */
    public function getChurchesUserMember(): Collection
    {
        $user = $this->getAuthenticatedUser();

        if(empty($user->member->church))
        {
            throw new AppException(
                MessagesEnum::USER_HAS_NO_CHURCH,
                Response::HTTP_BAD_REQUEST
            );
        }

        return collect($user->member->church);
    }

    /**
     * @return Collection
     */
    public function getModulesUserMember(): Collection
    {
        $user = $this->getAuthenticatedUser();

        return collect($user->module);
    }

    public function userPayloadIsEqualsAuthUser(string $userId): bool
    {
        return $this->getAuthenticatedUserId() == $userId;
    }
}