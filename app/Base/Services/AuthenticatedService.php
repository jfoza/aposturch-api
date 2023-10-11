<?php

namespace App\Base\Services;

use App\Exceptions\AppException;
use App\Features\Module\Modules\Models\Module;
use App\Features\Users\Profiles\Models\Profile;
use App\Modules\Membership\Church\Models\Church;
use App\Shared\Enums\MessagesEnum;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

abstract class AuthenticatedService extends BaseService
{
    private object $authenticatedUser;

    /**
     * @param object $authenticatedUser
     */
    public function setAuthenticatedUser(object $authenticatedUser): void
    {
        $this->authenticatedUser = $authenticatedUser;
    }

    /**
     * @return object
     */
    public function getAuthenticatedUser(): object
    {
        return $this->authenticatedUser;
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
     * @throws AppException
     */
    public function getUserMemberChurchesId(): array
    {
        return $this->getChurchesUserMember()->pluck(Church::ID)->toArray();
    }

    /**
     * @return Collection
     */
    public function getModulesUser(): Collection
    {
        $user = $this->getAuthenticatedUser();

        return collect($user->module);
    }

    public function getUserModulesId(): array
    {
        return $this->getModulesUser()->pluck(Module::ID)->toArray();
    }

    public function getProfilesUser(): Collection
    {
        $user = $this->getAuthenticatedUser();

        return collect($user->profile);
    }

    public function getProfilesId(): array
    {
        return $this->getProfilesUser()->pluck(Profile::ID)->toArray();
    }

    public function userPayloadIsEqualsAuthUser(string $userId): bool
    {
        return $this->getAuthenticatedUserId() == $userId;
    }

    /**
     * @throws AppException
     */
    public function canAccessTheChurch(array $churchesId, string $message = null): void
    {
        if(empty($this->getChurchesUserMember()->whereIn(Church::ID, $churchesId)->all()))
        {
            throw new AppException(
                !is_null($message) ? $message : MessagesEnum::NO_ACCESS_TO_CHURCH,
                Response::HTTP_FORBIDDEN
            );
        }
    }

    /**
     * @throws AppException
     */
    public function canAccessModules(array $modulesId): void
    {
        if(empty($this->getModulesUser()->whereIn(Module::ID, $modulesId)->all()))
        {
            throw new AppException(
                MessagesEnum::MODULE_NOT_ALLOWED,
                Response::HTTP_FORBIDDEN
            );
        }
    }

    /**
     * @throws AppException
     */
    public function canAccessEachModules(array $modulesId): void
    {
        foreach ($modulesId as $moduleId)
        {
            if(empty($this->getModulesUser()->where(Module::ID, $moduleId)->first()))
            {
                throw new AppException(
                    MessagesEnum::MODULE_NOT_ALLOWED,
                    Response::HTTP_FORBIDDEN
                );
            }
        }
    }

    /**
     * @throws AppException
     */
    public function canAccessProfile(string $profileId): void
    {
        if(empty($this->getProfilesUser()->where(Profile::ID, $profileId)->first()))
        {
            throw new AppException(
                MessagesEnum::PROFILE_NOT_ALLOWED,
                Response::HTTP_FORBIDDEN
            );
        }
    }
}
